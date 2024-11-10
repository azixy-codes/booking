<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Booking;
use App\Enum\BookingStatusEnum;
use App\Form\FullBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/booking', name: 'home.booking', methods: ['GET', 'POST'])]
    public function makeBooking(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FullBookingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setName($form->get('name')->getData());
            $user->setEmail($form->get('email')->getData());

            $booking = new Booking();
            $booking->setUser($user);
            $booking->setSpot($form->get('spot')->getData());
            $booking->setTimeslot($form->get('timeslot')->getData());
            $booking->setStatus(BookingStatusEnum::PENDING);
            $booking->setDate(\DateTimeImmutable::createFromMutable($form->get('date')->getData()));

            if ($this->isNotAvailable($booking)) {
                $this->addFlash('error', 'Le créneau sélectionné est déjà réservé.');
                return $this->redirect('back');
            }

            $entityManager->persist($user);
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('bookings.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/booking.html.twig', compact('form'));
    }

    #[Route('/administration', name: 'administration')]
    public function administration()
    {
        return $this->render('/administration/index.html.twig');
    }

    public function isNotAvailable(Booking $booking, ): bool
    {
        $booking = $this->entityManager->getRepository(Booking::class)
            ->findOneBy([
                'spot' => $booking->getSpot(),
                'timeslot' => $booking->getTimeslot(),
                'date' => $booking->getDate(),
            ]);

        return $booking !== null;
    }
}
