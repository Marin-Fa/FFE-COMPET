<?php

namespace App\DataFixtures;

use App\Entity\Contest;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContestFixtures extends Fixture implements DependentFixtureInterface
{
    public const GROUP_CONTEST = 'contest';

    public function load(ObjectManager $manager)
    {
        $contests = $this->getDataForContests();
        for ($i = 0; $i < count($contests); $i++) {
            $contest = new Contest();
            $contest->setStableName($contests[$i]['stable_name']);
            $contest->setAdress($contests[$i]['adress']);
            $contest->setZipcode($contests[$i]['zipcode']);
            $contest->setCity($contests[$i]['city']);
            $contest->setCountry($contests[$i]['country']);
            $contest->setCreationDate(new DateTime('now'));
            $contest->setEndOfRegistration($contests[$i]['end_of_registration']);
            $contest->setDiscipline($contests[$i]['discipline']);
            $contest->setMaxContestantsTotal($contests[$i]['max_contestant_total']);
            $contest->setBeginningDate($contests[$i]['beginning_date']);
            $contest->setEndDate($contests[$i]['end_date']);
            $contest->setPicture($contests[$i]['picture']);
            $contest->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            $this->setReference(self::GROUP_CONTEST, $contest);

            $manager->persist($contest);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(UserFixtures::class);
    }

    public function getDataForContests()
    {
        return [
            [
                'stable_name' => 'Ecuries de Peneloppe Leprevost',
                'adress' => 'route du lac',
                'zipcode' => '53100',
                'city' => 'Normandie',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'HUNTER',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'peneloppe.jpeg'
            ],
            [
                'stable_name' => 'Domaine de la Sauvageonne',
                'adress' => 'Route de longsard',
                'zipcode' => '69400',
                'city' => 'Arnas',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'CSO',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'sauvageonne.jpg'
            ],
            [
                'stable_name' => 'Longines Paris Eiffel Jumping',
                'adress' => 'Champs de mars',
                'zipcode' => '01100',
                'city' => 'Paris',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'CSO',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'paris.jpg'
            ],
            [
                'stable_name' => 'LONGINES INTERNATIONAL JUMPING LA BAULE 2020',
                'adress' => '5 Avenue des Rosières',
                'zipcode' => '74150',
                'city' => 'La Baule-Escoublac',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'CSO',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'la_baule.jpg'
            ],
            [
                'stable_name' => 'Ecuries de Boistray',
                'adress' => 'route de boistray',
                'zipcode' => '69400',
                'city' => 'Saint Georges Reneins',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'DRESSAGE',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'boistray.jpg'
            ],
            [
                'stable_name' => 'Centre Equestre de Denice',
                'adress' => 'route de denice',
                'zipcode' => '69460',
                'city' => 'Denice',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'HORSE-BALL',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'denice.jpeg'
            ],
            [
                'stable_name' => 'Le Tournebride',
                'adress' => '45 Chemin de la Beffe',
                'zipcode' => '69570',
                'city' => 'Dardilly',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'ENDURANCE',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'tournebride.jpg'
            ],
            [
                'stable_name' => 'Les Ecuries de louest',
                'adress' => '64 Chemin du Creux du Lac',
                'zipcode' => '69380',
                'city' => 'Dommartin',
                'country' => 'France',
                'end_of_registration' => new \DateTime("+2 months"),
                'discipline' => 'CCE',
                'max_contestant_total' => 200,
                'beginning_date' => new \DateTime("+3 months"),
                'end_date' => new \DateTime("+3 months"),
                'picture' => 'louest.jpg'
            ],
        ];
    }
}