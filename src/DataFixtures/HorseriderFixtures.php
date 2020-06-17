<?php

namespace App\DataFixtures;

use App\Entity\Horserider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HorseriderFixtures extends Fixture implements DependentFixtureInterface
{
    public const GROUP_HORSERIDER = 'horserider';

    public function load(ObjectManager $manager)
    {
        $startNumber = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        for ($i = 0; $i < count($startNumber); $i++) {
            $horserider = new Horserider();
            $horserider->setStartNumber($startNumber[$i]);
            $horserider->setEvent($this->getReference(EventFixtures::GROUP_EVENT));
            $horserider->setUser($this->getReference(UserFixtures::GROUP_RIDER));
            $horserider->setHorse($this->getReference(HorseFixtures::GROUP_HORSE));
            $this->setReference(self::GROUP_HORSERIDER, $horserider);

            $manager->persist($horserider);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(UserFixtures::class,ContestFixtures::class,EventFixtures::class);
    }
}
