<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\Specialty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DoctorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $doctor1 = new Doctor();
        $doctor1->setFirstName('Jack');
        $doctor1->setLastName('Smith');
        $doctor1->setPhoto('doctor-1.jpg');
        $doctor1->setPhone('123-456-7890');
        $doctor1->setEmail('jack.smith@salutem.fr');
        $doctor1->setDescription('Dr. Jack Smith is a highly skilled and compassionate physician with over 15 years of experience in internal medicine. He is dedicated to providing personalized care to his patients and staying up-to-date with the latest medical advancements.');
        $doctor1->addSpecialty($this->getReference('homeopathe', Specialty::class));
        $doctor1->addSpecialty($this->getReference('osteopathe', Specialty::class));
        $manager->persist($doctor1);

        $doctor2 = new Doctor();
        $doctor2->setFirstName('Norma');
        $doctor2->setLastName('Pedric');
        $doctor2->setPhoto('doctor-2.jpg');
        $doctor2->setPhone('987-654-3210');
        $doctor2->setEmail('norma.pedric@salutem.fr');
        $doctor2->setDescription('Dr. Norma Pedric is a dedicated and experienced physician with a strong focus on patient care and satisfaction.');
        $doctor2->addSpecialty($this->getReference('medecin_generaliste', Specialty::class));
        $manager->persist($doctor2);

        $this->addReference('doctor', $doctor2);

        $doctor3 = new Doctor();
        $doctor3->setFirstName('Maria');
        $doctor3->setLastName('Martin');
        $doctor3->setPhoto('doctor-3.jpg');
        $doctor3->setPhone('555-123-4567');
        $doctor3->setEmail('maria.martin@salutem.fr');
        $doctor3->setDescription('Dr. Maria Martin is a compassionate and skilled physician with a passion for providing exceptional patient care.');
        $doctor3->addSpecialty($this->getReference('dentiste', Specialty::class));
        $manager->persist($doctor3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpecialtyFixtures::class,
        ];
    }
}
