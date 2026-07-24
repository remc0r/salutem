<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DoctorController extends AbstractController
{
    public function __construct(
        private readonly DoctorRepository       $doctorRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/doctor/{id}', name: 'app_doctor', requirements: ['id' => '\\d+'])]
    public function show(#[MapEntity(id: 'id')] Doctor $doctor): Response
    {
        return $this->render('doctor/index.html.twig', [
            'controller_name' => 'DoctorController',
            'doctor' => $doctor,
        ]);
    }

    #[Route('/doctors', name: 'app_doctors')]
    public function allDoctors(): Response
    {
        $doctors = $this->doctorRepository->findAll();
        return $this->render('doctor/all.html.twig', [
            'controller_name' => 'DoctorController',
            'doctors' => $doctors,
        ]);
    }

    #[Route('/doctor/new', name: 'app_doctor_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $req, FileUploader $fileUploader): Response
    {

        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();

            if ($file) {
                $fileName = $fileUploader->upload($file);
                $doctor->setPhoto($fileName);
            }

            $this->entityManager->persist($doctor);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_doctors');
        }

        return $this->render('doctor/new.html.twig', [
            'form' => $form,
        ]);
    }

}
