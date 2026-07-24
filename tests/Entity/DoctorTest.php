<?php

namespace App\Tests\Entity;

use App\Entity\Doctor;
use PHPUnit\Framework\TestCase;

class DoctorTest extends TestCase
{

    public function testGetFullName()
    {
        $doctor = new Doctor();
        $doctor->setFirstName("John");
        $doctor->setLastName("Doctor");
        $fullName = $doctor->getFullName();
        $this->assertIsString($fullName);
        $this->assertEquals("John Doctor", $fullName);
    }
}
