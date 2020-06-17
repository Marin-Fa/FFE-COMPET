<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/user", name="user")
     * @param UserRepository $userRepo
     * @param Request $request
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersList(UserRepository $userRepo, Request $request, UserInterface $user)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $users = $userRepo->findAll(); // array
        $roles = $propertyAccessor->getValue($user, 'roles');
        $roleRider = $userRepo->findByRole('ROLE_RIDER');
        $roleOrga = $userRepo->findByRole('ROLE_ORGANIZER');
        $roleAdmin = $userRepo->findByRole('ROLE_ADMIN');

        return $this->render('admin/user.html.twig', [
            'users' => $users,
            'roles' => $roles,
            'roleRider' => $roleRider,
            'roleOrga' => $roleOrga,
            'roleAdmin' => $roleAdmin
        ]);
    }

    /**
     * @Route("/user/modify/{id}", name="modify_user", methods={"GET","POST"})
     * @param UserRepository $userRepository
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editUser(UserRepository $userRepository, $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form['roles']->getData();
            $user->setRoles([$role]);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('message', 'User modify with success');
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user", methods={"DELETE"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function deleteUser(User $user, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user');
    }
}
