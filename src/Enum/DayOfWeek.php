<?php

namespace App\Enum;

enum DayOfWeek: string
{
    case MONDAY = 'lundi';
    case TUESDAY = 'mardi';
    case WEDNESDAY = 'mercredi';
    case THURSDAY = 'jeudi';
    case FRIDAY = 'vendredi';
    case SATURDAY = 'samedi';
    case SUNDAY = 'dimanche';

    public static function today(): self
    {
        $englishDay = strtolower((new \DateTimeImmutable('today'))->format('l'));

        return self::fromEnglishDay($englishDay);
    }

    public static function fromEnglishDay(string $englishDay): self
    {
        return match (strtolower($englishDay)) {
            'monday' => self::MONDAY,
            'tuesday' => self::TUESDAY,
            'wednesday' => self::WEDNESDAY,
            'thursday' => self::THURSDAY,
            'friday' => self::FRIDAY,
            'saturday' => self::SATURDAY,
            'sunday' => self::SUNDAY,
            default => self::MONDAY,
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::MONDAY => 1,
            self::TUESDAY => 2,
            self::WEDNESDAY => 3,
            self::THURSDAY => 4,
            self::FRIDAY => 5,
            self::SATURDAY => 6,
            self::SUNDAY => 7,
        };
    }
}

