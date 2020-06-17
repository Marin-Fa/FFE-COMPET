<?php

namespace App\Controller;

use App\Entity\Horse;
use App\Entity\User;
use App\Form\HorseFormType;
use App\Form\SelectHorseFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HorseController extends AbstractController
{
    public function horse(ValidatorInterface $validator)
    {
        $horse = new Horse();

        $errors = $validator->validate($horse);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        return new Response('The horse is valid! Yes!');
    }

    /**
     * @Route("user/{id}/horse", name="user_horses")
     * @param UserRepository $repo
     * @param $id
     * @return Response
     */
    public function userHorses(UserRepository $repo, $id)
    {
        $user = $repo->find($id);
        $horses = $user->getHorses();

        return $this->render('horse/horse.html.twig', [
            'controller_name' => 'HorseController',
            'horses' => $horses,
            'user' => $user
        ]);
    }

    /**
     * @Route("horse/user/{id}", name="horse_compet")
     * @param User $user
     * @param Request $request
     * @param Horse $horse
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function userHorseForEvent(User $user, Request $request, Horse $horse)
    {
        $form = $this->createForm(SelectHorseFormType::class, $horse);
        $form->handleRequest($request);
        $user = $this->getUser();
        $userHorses = $user->getHorses();
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info', 'We selected a horse with id ' . $horse->getId());

            return $this->redirectToRoute('user_horses');
        }
        return $this->render('horse/selecthorse.html.twig', [
            'selectHorseForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/horse/new", name="horse_add")
     * @param Request $request
     * @param Horse|null $horse
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addHorse(Request $request, Horse $horse = null, EntityManagerInterface $entityManager)
    {
        $horse = new Horse();
        $form = $this->createForm(HorseFormType::class, $horse);
        $form->handleRequest($request);

        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $horse->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($horse);
            $entityManager->flush();

            return $this->redirectToRoute('user_horses', ['id' => $user->getId()]);
        }

        return $this->render('horse/index.html.twig', [
            'controller_name' => 'HorseController',
            'horseForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/horse{id}", name="horse_delete")
     * @param Request $request
     * @param Horse $horse
     * @return Response
     */
    public function deleteHorse(Request $request, Horse $horse): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$horse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($horse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_horses', ['id' => $user->getId()]);
    }
}
