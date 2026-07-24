<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference('user_patient', User::class);

        /** @var Doctor $doctor */
        $doctor = $this->getReference('doctor', Doctor::class);

        $appointment = new Appointment();

        $appointment->setDoctor($doctor)
            ->setPatientName('Doe')
            ->setPatientFirstName('John')
            ->setPatientMail('patient@patient.fr')
            ->setPatientPhoneNumber('1234567890')
            ->setDate(new \DateTimeImmutable('2024-07-01 10:00:00'))
            ->setMessage('This is a test appointment.')
            ->setUser($user)
            ->setSpecialty($doctor->getSpecialties()->first());


        $manager->persist($appointment);
        $manager->flush();
    }
}
