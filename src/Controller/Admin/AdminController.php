<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Appointment;
use App\Form\UserType;
use App\Repository\AppointmentRepository;
use App\Repository\DoctorRepository;
use App\Repository\UserRepository;
use App\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    private const AVAILABLE_ROLES = [
        'ROLE_USER' => 'Utilisateur',
        'ROLE_ADMIN' => 'Administrateur',
        'ROLE_DOCTOR' => 'Docteur',
    ];

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

    #[Route('/users', name: 'admin_users_index', methods: ['GET'])]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
            'available_roles' => self::AVAILABLE_ROLES,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'role_choices' => array_flip(self::AVAILABLE_ROLES),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedRoles = array_values(array_filter(
                $user->getRoles(),
                static fn (string $role): bool => array_key_exists($role, self::AVAILABLE_ROLES)
            ));

            $user->setRoles($selectedRoles);

            $entityManager->flush();

            $this->addFlash('success', sprintf('Les rôles de %s ont été mis à jour.', $user->getEmail()));

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/user_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
