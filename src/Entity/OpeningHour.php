<?php

namespace App\Entity;

use App\Enum\DayOfWeek;
use App\Repository\OpeningHourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OpeningHourRepository::class)]
#[UniqueEntity(fields: ['day'], message: 'Un horaire d\'ouverture existe déjà pour ce jour.')]
class OpeningHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true, enumType: DayOfWeek::class)]
    #[Assert\NotNull(message: 'Le jour doit être spécifié.')]
    private ?DayOfWeek $day = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotNull(message: 'L\'ordre du jour doit être spécifié.')]
    #[Assert\Range(
        min: 1,
        max: 7,
        notInRangeMessage: 'L\'ordre du jour doit être entre 1 et 7.'
    )]
    private ?int $dayOrder = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $opensAt = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $closesAt = null;

    #[ORM\Column]
    private bool $isClosed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?DayOfWeek
    {
        return $this->day;
    }

    public function setDay(DayOfWeek $day): static
    {
        $this->day = $day;
        $this->dayOrder = $day->order();

        return $this;
    }

    public function getDayOrder(): ?int
    {
        return $this->dayOrder;
    }

    public function setDayOrder(int $dayOrder): static
    {
        $this->dayOrder = $dayOrder;

        return $this;
    }

    public function getOpensAt(): ?\DateTimeImmutable
    {
        return $this->opensAt;
    }

    public function setOpensAt(?\DateTimeImmutable $opensAt): static
    {
        $this->opensAt = $opensAt;

        return $this;
    }

    public function getClosesAt(): ?\DateTimeImmutable
    {
        return $this->closesAt;
    }

    public function setClosesAt(?\DateTimeImmutable $closesAt): static
    {
        $this->closesAt = $closesAt;

        return $this;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): static
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function getFormattedHours(): string
    {
        if ($this->isClosed || null === $this->opensAt || null === $this->closesAt) {
            return 'Ferme';
        }

        return $this->opensAt->format('G\\h') . ' - ' . $this->closesAt->format('G\\h');
    }
}

