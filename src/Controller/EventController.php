<?php

namespace App\Controller;

use App\Entity\Horserider;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Contest;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\ContestRepository;
use App\Repository\HorseriderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $events = $repo->findAll();
//        dump($events);
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
            'events' => $events
        ]);
    }
    /**
     * @Route("/event/contest/{id}", name="event_id")
     */
    public function EventOnContest()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $events = $repo->findAll();
//        dump($events);
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
            'events' => $events
        ]);
    }

    /**
     * @Route("/contest/{id}/event/new", name="event_create")
     * @param Request $request
     * @param Event|null $event
     * @param EntityManagerInterface $entityManager
     * @param ContestRepository $repo
     * @param $id
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createEvent(Request $request, Event $event = null, EntityManagerInterface $entityManager, ContestRepository $repo, $id)
    {
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        
        $contest = $repo->find($id);
        
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$event->getId()) {
                $event->setDate(new \DateTime());
                $event->setContest($contest);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }
//        dump($contest);
        return $this->render('event/add-event.html.twig', [
            'eventForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("event/{id}/edit", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'contestForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/{id}", name="event_show", methods={"GET"})
     * @param EventRepository $eventRepository
     * @param $id
     * @param HorseriderRepository $horseriderRepository
     * @return Response
     * @throws Exception
     */
    public function show(EventRepository $eventRepository, $id, HorseriderRepository $horseriderRepository): Response
    {
        $event = $eventRepository->find($id);
        $user = $this->getUser();
        $horseriders = $event->getHorseriders();
        $eventHorseriders = $horseriderRepository->findByIdEvent($event);
        $maxContestants = $event->getMaxContestants();
        $contest = $event->getContest()->getId();
        $endOfRegistration =  $event->getContest()->getEndOfRegistration();
        $dateNow = new \DateTime();
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'horseriders' => $horseriders,
            'contest' => $contest,
            'dateNow' => $dateNow,
            'eventHorseriders' => $eventHorseriders,
            'user' => $user,
            'endOfRegistration' => $endOfRegistration
        ]);
    }

    /**
     * @Route("/event/{id}", name="event_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {
        $contest = $event->getContest();
//        dump($contest);
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contest_events', ['id' => $contest->getId()]);
    }
}
