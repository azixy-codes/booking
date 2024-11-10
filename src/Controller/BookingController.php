<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/administration/bookings', 'bookings.')]
final class BookingController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('administration/booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);

            if ($this->isNotAvailable($booking)) {
                $this->addFlash('error', 'Le créneau sélectionné est déjà réservé.');
                return $this->redirect('back');
            }

            $entityManager->flush();

            $this->addFlash('success', 'Réservation ajoutée avec succès.');

            return $this->redirectToRoute('bookings.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Booking $booking): Response
    {
        return $this->render('administration/booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isNotAvailable($booking)) {
                $this->addFlash('error', 'Le créneau sélectionné est déjà réservé.');
                return $this->redirect('back');
            }
            $entityManager->flush();

            $this->addFlash('success', 'Réservation editée avec succès.');
            return $this->redirectToRoute('bookings.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Booking $booking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Réservation supprimée avec succès.');

        return $this->redirectToRoute('bookings.index', [], Response::HTTP_SEE_OTHER);
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
