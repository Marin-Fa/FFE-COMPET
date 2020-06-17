<?php


namespace App\Service;


use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\Role;

class ReversedRoleHierarchy extends RoleHierarchy
{
    public function __construct(array $hierarchy)
    {
        $reversed = [];
        foreach ($hierarchy as $main => $roles) {
            foreach ($roles as $role) {
                $reversed[$role][] = $main;
            }
        }
        parent::__construct($hierarchy);
    }

    public function getParentRoles(array $roleNames)
    {
        $roles = [];
        foreach ($roleNames as $roleName) {
            $roles[] = new Role($roleName);
        }
        $results = [];
        foreach ($this->getReachableRoleNames($roles) as $parent) {
            $results[] = $parent->getRole();
        }
        return $results;
    }
}