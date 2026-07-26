<?php

namespace App\Controller\Admin;

use App\Entity\OpeningHour;
use App\Form\OpeningHourType;
use App\Repository\OpeningHourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/opening-hours')]
#[IsGranted('ROLE_ADMIN')]
final class OpeningHourController extends AbstractController
{
    #[Route(name: 'admin_opening_hours_index', methods: ['GET'])]
    public function index(OpeningHourRepository $openingHourRepository): Response
    {
        return $this->render('admin/opening_hours.html.twig', [
            'opening_hours' => $openingHourRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_opening_hour_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $openingHour = new OpeningHour();
        $form = $this->createForm(OpeningHourType::class, $openingHour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($openingHour);
            $entityManager->flush();

            return $this->redirectToRoute('admin_opening_hours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/opening_hour_form.html.twig', [
            'opening_hour' => $openingHour,
            'form' => $form,
            'title' => 'Créer un horaire d\'ouverture',
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_opening_hour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OpeningHour $openingHour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpeningHourType::class, $openingHour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_opening_hours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/opening_hour_form.html.twig', [
            'opening_hour' => $openingHour,
            'form' => $form,
            'title' => 'Modifier l\'horaire d\'ouverture',
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_opening_hour_delete', methods: ['POST'])]
    public function delete(Request $request, OpeningHour $openingHour, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$openingHour->getId(), $token)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_opening_hours_index');
        }

        $day = $openingHour->getDay()?->value ?? 'cet horaire';
        $entityManager->remove($openingHour);
        $entityManager->flush();

        $this->addFlash('success', sprintf('L\'horaire d\'ouverture pour %s a été supprimé.', $day));

        return $this->redirectToRoute('admin_opening_hours_index');
    }
}

