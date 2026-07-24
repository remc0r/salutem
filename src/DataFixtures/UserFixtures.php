<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function getDependencies(): array
    {
        return [
            DoctorFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Doctor $doctor */
        $doctor_fixtures = $this->getReference('doctor', Doctor::class);

        $admin = new User();

        $admin->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, "admin"));

        $doctor = new User();

        $doctor->setEmail('doctor@doctor.fr')
            ->setRoles(['ROLE_DOCTOR'])
            ->setPassword($this->hasher->hashPassword($doctor, "doctor"))
            ->setDoctor($doctor_fixtures);

        $patient = new User();

        $patient->setEmail('patient@patient.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->hasher->hashPassword($patient, "patient"));

        $this->addReference('user_patient', $patient);

        $manager->persist($admin);
        $manager->persist($patient);
        $manager->persist($doctor);
        $manager->flush();
    }
}
