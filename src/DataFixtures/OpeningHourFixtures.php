<?php

namespace App\DataFixtures;

use App\Entity\OpeningHour;
use App\Enum\DayOfWeek;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class OpeningHourFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $weeklyOpeningHours = [
            ['day' => DayOfWeek::MONDAY, 'opensAt' => '09:00', 'closesAt' => '17:00', 'isClosed' => false],
            ['day' => DayOfWeek::TUESDAY, 'opensAt' => '09:00', 'closesAt' => '17:00', 'isClosed' => false],
            ['day' => DayOfWeek::WEDNESDAY, 'opensAt' => '09:00', 'closesAt' => '17:00', 'isClosed' => false],
            ['day' => DayOfWeek::THURSDAY, 'opensAt' => '09:00', 'closesAt' => '17:00', 'isClosed' => false],
            ['day' => DayOfWeek::FRIDAY, 'opensAt' => '09:00', 'closesAt' => '17:00', 'isClosed' => false],
            ['day' => DayOfWeek::SATURDAY, 'opensAt' => '09:00', 'closesAt' => '15:00', 'isClosed' => false],
            ['day' => DayOfWeek::SUNDAY, 'opensAt' => null, 'closesAt' => null, 'isClosed' => true],
        ];

        foreach ($weeklyOpeningHours as $data) {
            $openingHour = $manager->getRepository(OpeningHour::class)->findOneBy(['day' => $data['day']]);

            if (!$openingHour instanceof OpeningHour) {
                $openingHour = new OpeningHour();
            }

            $openingHour
                ->setDay($data['day'])
                ->setDayOrder($data['day']->order())
                ->setIsClosed($data['isClosed'])
                ->setOpensAt($data['opensAt'] ? $this->createTime($data['opensAt']) : null)
                ->setClosesAt($data['closesAt'] ? $this->createTime($data['closesAt']) : null)
            ;

            $manager->persist($openingHour);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['opening_hours'];
    }

    private function createTime(string $time): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('!H:i', $time)
            ?: throw new \RuntimeException(sprintf('Invalid time format "%s".', $time));
    }
}

