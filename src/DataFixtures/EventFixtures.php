<?php

namespace App\DataFixtures;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public const GROUP_EVENT = 'event';

    public function load(ObjectManager $manager)
    {
        $events = $this->getDataForEvents();
        for ($i = 0; $i < count($events); $i++) {
            $event = new Event();
            $event->setDate($events[$i]['date']);
            $event->setDescription($events[$i]['description']);
            $event->setLevel($events[$i]['level']);
            $event->setEstimatedStartingTime($events[$i]['estimated_starting_time']);
            $event->setMaxContestants($events[$i]['max_contestants']);
            $event->setContest($this->getReference(ContestFixtures::GROUP_CONTEST));
            $this->setReference(self::GROUP_EVENT, $event);

            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(ContestFixtures::class);
    }

    public function getDataForEvents()
    {
        return [
            [
                'date' => new \DateTime(),
                'description' => 'Ponam D4',
                'level' => 'Galop 2',
                'estimated_starting_time' => new \DateTime(),
                'max_contestants' => 20,
            ],
            [
                'date' => new \DateTime(),
                'description' => 'Ponam D3',
                'level' => 'Galop 3',
                'estimated_starting_time' => new \DateTime(),
                'max_contestants' => 20,
            ],
            [
                'date' => new \DateTime(),
                'description' => 'GP 5*',
                'level' => 'Pro Elite',
                'estimated_starting_time' => new \DateTime(),
                'max_contestants' => 20,
            ],
            [
                'date' => new \DateTime(),
                'description' => 'Vitesse 125',
                'level' => 'Amateur 3',
                'estimated_starting_time' => new \DateTime(),
                'max_contestants' => 20,
            ],
            [
                'date' => new \DateTime(),
                'description' => 'GP 140',
                'level' => 'Amateur 1',
                'estimated_starting_time' => new \DateTime(),
                'max_contestants' => 20,
            ],
        ];
    }

}
