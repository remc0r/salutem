<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\DoctorRepository;
use App\Service\AppointmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/appointments', name: 'admin_appointments_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository, DoctorRepository $doctorRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
            'doctors' => $doctorRepository->findAll(),
        ]);
    }

    #[Route('/appointments/{id}/assign', name: 'admin_appointment_assign', methods: ['POST'])]
    public function assign(Request $request, Appointment $appointment, DoctorRepository $doctorRepository, AppointmentService $appointmentService): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('assign'.$appointment->getId(), $token)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_appointments_index');
        }

        $doctorId = $request->request->get('doctor');
        if (!$doctorId) {
            $this->addFlash('warning', 'Veuillez sélectionner un docteur.');
            return $this->redirectToRoute('admin_appointments_index');
        }

        $doctor = $doctorRepository->find($doctorId);
        if (!$doctor) {
            $this->addFlash('danger', 'Docteur introuvable.');
            return $this->redirectToRoute('admin_appointments_index');
        }

        $appointmentService->assignToDoctor($appointment, $doctor);

        $this->addFlash('success', 'Rendez-vous affecté au docteur ' . $doctor->getFullName() . '.');

        return $this->redirectToRoute('admin_appointments_index');
    }

    #[Route('/appointments/{id}/delete', name: 'admin_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, AppointmentService $appointmentService): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$appointment->getId(), $token)) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_appointments_index');
        }

        $appointmentService->deleteAppointment($appointment);

        $this->addFlash('success', 'Rendez-vous supprimé.');

        return $this->redirectToRoute('admin_appointments_index');
    }
}


