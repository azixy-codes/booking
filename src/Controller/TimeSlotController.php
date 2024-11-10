<?php

namespace App\Controller;

use App\Entity\TimeSlot;
use App\Form\TimeSlotType;
use App\Repository\TimeSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/administration/time-slots', 'timeSlots.')]
final class TimeSlotController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(TimeSlotRepository $timeSlotRepository): Response
    {
        return $this->render('administration/time_slot/index.html.twig', [
            'time_slots' => $timeSlotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timeSlot = new TimeSlot();
        $form = $this->createForm(TimeSlotType::class, $timeSlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeSlot);
            $entityManager->flush();

            $this->addFlash('success', 'Créneau horaire ajouté avec succès.');
            return $this->redirectToRoute('timeSlots.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('administration/time_slot/new.html.twig', [
            'time_slot' => $timeSlot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeSlot $timeSlot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeSlotType::class, $timeSlot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Créneau horaire edité avec succès.');

            return $this->redirectToRoute('timeSlots.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/time_slot/edit.html.twig', [
            'time_slot' => $timeSlot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, TimeSlot $timeSlot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $timeSlot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($timeSlot);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Créneau horaire supprimé avec succès.');
        return $this->redirectToRoute('timeSlots.index', [], Response::HTTP_SEE_OTHER);
    }
}
