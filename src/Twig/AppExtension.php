<?php

namespace App\Twig;

use App\Entity\OpeningHour;
use App\Enum\DayOfWeek;
use App\Repository\OpeningHourRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly OpeningHourRepository $openingHourRepository,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getDay', [$this, 'getDay']),
            new TwigFunction('getOpeningHours', [$this, 'getOpeningHours']),
            new TwigFunction('isOpen', [$this, 'isOpen']),
            new TwigFunction('getTodayOpeningHours', [$this, 'getTodayOpeningHours']),

        ];
    }

    public function getDay(): DayOfWeek
    {
        return DayOfWeek::today();
    }

    public function getOpeningHours(): array
    {
        return $this->openingHourRepository->findAllOrdered();
    }

    public function getTodayOpeningHours(): OpeningHour
    {
        return $this->openingHourRepository->findOneBy(['day' => DayOfWeek::today()]);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function isOpen(OpeningHour $openingHour): bool
    {
        $now = new \DateTimeImmutable(timezone: new \DateTimeZone('CEST'));
        $currentTime = $now->format('H:i:s');

        if ($openingHour->isClosed()) {
            return false;
        }

        $opensAt = $openingHour->getOpensAt()?->format('H:i:s');
        $closesAt = $openingHour->getClosesAt()?->format('H:i:s');

        return $opensAt <= $currentTime && $currentTime <= $closesAt;
    }
}
