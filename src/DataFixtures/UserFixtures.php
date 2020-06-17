<?php

namespace App\DataFixtures;

use App\Entity\Contest;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const ADMIN_USER_REFERENCE = 'ROLE_ADMIN';
    public const GROUP_ORGANIZER = 'organizer';
    public const GROUP_RIDER = 'rider';
//    public const GROUP_EVENT = 'event';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
        $firstname = ['Sam', 'Jean', 'Pierre'];
        $lastname = ['Paul', 'Marie', 'Jeanne', 'Anna', 'Luc', 'Joe', 'Suzie', 'Alan', 'Carol', 'Sonia', 'khaled', 'Younes', 'Kobe', 'John', 'Tracy'];
        // Organizer
        for ($i = 0; $i < count($firstname); $i++) {
            $user = new User();
            $user->setFirstname($firstname[$i]);
            $user->setLastname($firstname[$i]);
            $user->setEmail(strtolower($firstname[$i] . '@test.com'));
            $hash = $this->passwordEncoder->encodePassword($user, strtolower($firstname[$i]));
            $user->setPassword($hash);
            $user->setRoles(['ROLE_ORGANIZER']);
            $user->setLicenceNumber('123456');
            $this->setReference(self::GROUP_ORGANIZER, $user);
            $manager->persist($user);
        }
        // Rider
        for ($i = 0; $i < count($lastname); $i++) {
            $user = new User();
            $user->setFirstname($lastname[$i]);
            $user->setLastname($lastname[$i]);
            $user->setEmail(strtolower($lastname[$i] . '@test.com'));
            $hash = $this->passwordEncoder->encodePassword($user, strtolower($lastname[$i]));
            $user->setPassword($hash);
            $lastname[$i] === 'Sam' ? $user->setRoles(['ROLE_ADMIN']) : $user->setRoles(['ROLE_RIDER']);
            $user->setLicenceNumber('456985');
            $this->setReference(self::GROUP_RIDER, $user);
            $manager->persist($user);
        }
        // Admin
        $user = new User();
        $user->setFirstname('Marine');
        $user->setLastname('Fayolle');
        $user->setEmail(strtolower('marin@symfo.com'));
        $user->setRoles(['ROLE_ADMIN']);
        $hash = $this->passwordEncoder->encodePassword($user, strtolower('password'));
        $user->setPassword($hash);
        $user->setLicenceNumber('789456');
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
        $manager->persist($user);
        $manager->flush();
    }
}
