<?php

namespace App\Security\Voter;

use App\Entity\Contest;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const ROLES = 'roles';

    private $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }
        if (!$subject instanceof Contest) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        /** @var Contest $contest */
        $contest = $subject;

        foreach ($token->getRoleNames() as $role) {
            if (in_array($role->getRoleNames(), ['ROLE_ADMIN', 'ROLE_ORGANIZER'])) {
                return true;
            }
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($contest, $user);
                break;
            case self::EDIT:
                return $this->canEdit($contest, $user);
                break;
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Contest $contest, User $user)
    {
        if ($this->canEdit($contest, $user)) {
            return true;
        }
        return !$contest->isPrivate();
    }

    private function canEdit(Contest $contest, User $user)
    {
        return $user === $contest->getUser();
    }

    protected function extractRoles(User $user, TokenInterface $token, $roleName)
    {
        $group = $token->getUser()->getRoles();
        return $this->getReachableRoles($group);
    }

//    public function getReachableRoles(Group $group, &$groups = array()) {
//
//        $groups[] = $group;
//
//        $children = $this->em->getRepository('App\Entity\User:User')->createQueryBuilder('u')
//            ->where('g.parent = :group')
//            ->setParameter('group', $group->getId())
//            ->getQuery()
//            ->getResult();
//
//        foreach($children as $child) {
//            $this->getReachableRoles($child, $groups);
//        }
//        return $groups;
//    }
    private function createRoleHierarchy($config, ContainerBuilder $container)
    {
        if (!isset($config['role_hierarchy'])) {
            $container->removeDefinition('security.access.role_hierarchy_voter');
            return;
        }
        $container->setParameter('security.role_hierarchy.roles', $config['role_hierarchy']);
        $container->removeDefinition('security.access.simple_role_voter');
    }
}
