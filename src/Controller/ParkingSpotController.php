<?php

namespace App\Controller;

use App\Entity\ParkingSpot;
use App\Form\ParkingSpotType;
use App\Repository\ParkingSpotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/administration/parking-spots', 'parkingSpots.')]
final class ParkingSpotController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(ParkingSpotRepository $parkingSpotRepository): Response
    {
        return $this->render('administration/parking_spot/index.html.twig', [
            'parkingSpots' => $parkingSpotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $parkingSpot = new ParkingSpot();
        $form = $this->createForm(ParkingSpotType::class, $parkingSpot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($parkingSpot);
            $entityManager->flush();
            $this->addFlash('success', 'Place de parking ajoutée avec succès.');

            return $this->redirectToRoute('parkingSpots.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/parking_spot/new.html.twig', [
            'parking_spot' => $parkingSpot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParkingSpot $parkingSpot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParkingSpotType::class, $parkingSpot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Place de parking editée avec succès.');
            return $this->redirectToRoute('parkingSpots.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/parking_spot/edit.html.twig', [
            'parking_spot' => $parkingSpot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, ParkingSpot $parkingSpot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parkingSpot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($parkingSpot);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Place de parking supprimée avec succès.');
        return $this->redirectToRoute('parkingSpots.index', [], Response::HTTP_SEE_OTHER);
    }
}
