<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RolesTwigExtension extends AbstractExtension {
    public function getFilters() {
        return [
            new TwigFilter('getRoles', [$this, 'getRoles']),
            ];
    }

//    public function getRoles() {
//        $roles = [
//            'Admin' => 'ROLE_ADMIN',
//            'Organizer' => 'ROLE_ORGANIZER',
//            'Rider' => 'ROLE_RIDER'
//        ];
//
//        return $roles;
//    }
    public function getName() {
        return 'roles_filter_twig_extension';
    }

    public function getRoles(UserInterface $user) {
        return $user->getRoles();
    }
}