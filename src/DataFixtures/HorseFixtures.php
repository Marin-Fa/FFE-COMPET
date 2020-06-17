<?php

namespace App\DataFixtures;

use App\Entity\Horse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HorseFixtures extends Fixture implements DependentFixtureInterface
{
    public const GROUP_HORSE = 'horse';

    public function load(ObjectManager $manager)
    {
        $horses = ['Lincaba de Safray', 'Obelix depinoux', 'Infante Gringo', 'Ines de Kally', 'Fit For Fun', 'Nino des Buissonet',];
        $genders = ['Mare', 'Stallion', 'Gelding'];
        for ($i = 0; $i < count($horses); $i++) {
            $horse = new Horse();
            $horse->setName($horses[$i]);
            for ($k = 0; $k < count($genders); $k++) {
                $horse->setGender($genders[$k]);
            }
            $horse->setUser($this->getReference(UserFixtures::GROUP_RIDER));
            $this->setReference(self::GROUP_HORSE, $horse);
            $manager->persist($horse);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(UserFixtures::class);
    }
}
