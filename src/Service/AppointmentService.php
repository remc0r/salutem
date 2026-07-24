<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\DoctorRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

final class AppointmentService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AppointmentRepository  $appointmentRepository,
        private readonly DoctorRepository       $doctorRepository,
    )
    {
    }

    public function assignToDoctor(Appointment $appointment, $doctor): void
    {
        $appointment->setDoctor($doctor);
        $this->em->flush();
    }

    public function deleteAppointment(Appointment $appointment): void
    {
        $this->em->remove($appointment);
        $this->em->flush();
    }

    /**
     * @return Appointment[]
     */
    public function findAppointmentsForUser(User $user): array
    {
        if (!$user || !$user->getEmail()) {
            return [];
        }

        return $this->appointmentRepository->findBy(['user' => $user->getId()]);
    }

    /**
     * @return Appointment[]
     */
    public function findAppointmentsForDoctorUser(User $user): array
    {
        if (!$user || !$user->getEmail()) {
            return [];
        }

        $doctor = $this->doctorRepository->findOneBy(['email' => $user->getEmail()]);
        if (null === $doctor and null != $user->getDoctor()) {
            $doctor = $this->doctorRepository->find($user->getDoctor()->getId());
        }
        if (!$doctor) {
            return [];
        }

        return $this->appointmentRepository->findBy(['doctor' => $doctor]);
    }
}

