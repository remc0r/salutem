<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du patient ne peut pas être vide.')]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $patientName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom du patient ne peut pas être vide.')]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $patientFirstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'L\'emaill {{ value }} n\'est pas une adresse email valide',
    )]
    private ?string $patientMail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 10)]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'Le numéro doit contenir exactement 10 chiffres.')]
    private ?string $patientPhoneNumber = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan('now', message: 'La date doit être dans le futur')]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialty $specialty = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?Doctor $doctor = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientName(): ?string
    {
        return $this->patientName;
    }

    public function setPatientName(string $patientName): static
    {
        $this->patientName = $patientName;

        return $this;
    }

    public function getPatientFirstName(): ?string
    {
        return $this->patientFirstName;
    }

    public function setPatientFirstName(string $patientFirstName): static
    {
        $this->patientFirstName = $patientFirstName;

        return $this;
    }

    public function getPatientMail(): ?string
    {
        return $this->patientMail;
    }

    public function setPatientMail(string $patientMail): static
    {
        $this->patientMail = $patientMail;

        return $this;
    }

    public function getPatientPhoneNumber(): ?string
    {
        return $this->patientPhoneNumber;
    }

    public function setPatientPhoneNumber(string $patientPhoneNumber): static
    {
        $this->patientPhoneNumber = $patientPhoneNumber;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSpecialty(): ?Specialty
    {
        return $this->specialty;
    }

    public function setSpecialty(?Specialty $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
