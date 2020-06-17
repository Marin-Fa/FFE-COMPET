<?php

namespace App\Controller;

use App\Entity\Contest;
use App\Form\ContestFormType;
use App\Service\FileUploader;
use App\Repository\ContestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContestController extends AbstractController
{
    /**
     * @Route("/contest", name="contest")
     * @param ContestRepository $contestRepository
     * @param Request $request
     * @param Contest|null $contest
     * @param PaginatorInterface $paginator
     * @return Response
     * @throws \Exception
     */
    public function index(ContestRepository $contestRepository, Request $request, Contest $contest = null, PaginatorInterface $paginator)
    {
        $now = new \DateTime();
        $data = $this->getDoctrine()->getRepository(Contest::class)->findBy([], ['creation_date' => 'desc']);

        $contests = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('contest/index.html.twig', [
            'controller_name' => 'ContestController',
            'contests' => $contests,
            'now' => $now,
        ]);
    }

    /**
     * @Route("/contest/new", name="contest_create")
     * @param Request $request
     * @param Contest|null $contest
     * @param EntityManagerInterface $entityManager
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function createContest(Request $request, Contest $contest = null, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $contest = new Contest();
        $form = $this->createForm(ContestFormType::class, $contest);
        $form->handleRequest($request);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$contest->getId()) {
                $contest->setCreationDate(new \DateTime());
                $contest->setUser($user);
            }
            // Service FileUploader
            $pictureFile = $form['picture']->getData();
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $contest->setPicture($pictureFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contest);
            $entityManager->flush();

            return $this->redirectToRoute('contest_show', ['id' => $contest->getId()]);
        }

        return $this->render('contest/add-contest.html.twig', [
            'contestForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("contest/{id}/edit", name="contest_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Contest $contest
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Contest $contest, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ContestFormType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Service FileUploader
            $pictureFile = $form['picture']->getData();
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $contest->setPicture($pictureFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contest_show', ['id' => $contest->getId()]);
        }

        return $this->render('contest/edit.html.twig', [
            'contest' => $contest,
            'contestForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contest/{id}", name="contest_show")
     * @param Contest $contest
     * @return Response
     */
    public function show(Contest $contest)
    {
        return $this->render('contest/show.html.twig', [
            'contest' => $contest
        ]);
    }

    /**
     * @Route("/contest/{id}/events", name="contest_events")
     * @param ContestRepository $repo
     * @param $id
     * @return Response
     */
    public function ContestEvents(ContestRepository $repo, $id)
    {
        $contest = $repo->find($id);
        $events = $contest->getEvents();

        if($contest->getEvents()) {
            return $this->render('event/index.html.twig', [
                'contest' => $contest,
                'events' => $events
            ]);
        }
    }

    /**
     * @Route("/contest{id}", name="contest_delete", methods={"DELETE"})
     * @param Request $request
     * @param Contest $contest
     * @return Response
     */
    public function delete(Request $request, Contest $contest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contest');
    }
}
