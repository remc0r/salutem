<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DoctorRepository $doctorRepository, Request $req, EntityManagerInterface $em, #[CurrentUser] $user): Response
    {
        $doctors = $doctorRepository->findDoctorsWithSpecialties();

        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment, ['current_user' => $user]);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user) {
                // try to map user info to appointment fields when available
                if (method_exists($user, 'getEmail') && $appointment->getPatientMail() === null) {
                    $appointment->setPatientMail($user->getEmail());
                }
                if (method_exists($user, 'getUserIdentifier') && $appointment->getPatientName() === null) {
                    // We don't have separate name fields on User; only set email by default. Adjustments can be made if User has names.
                }

                $appointment->setUser($user);
            }
            $em->persist($appointment);
            $em->flush();
            $this->addFlash('info', 'Votre rendez-vous a été pris en compte !');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/index.html.twig', [
            'doctors' => $doctors,
            'form' => $form,
        ]);
    }
}
