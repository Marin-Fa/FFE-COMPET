<?php

namespace App\Controller;

use App\Entity\Horse;
use App\Entity\Event;
use App\Entity\Horserider;
use App\Entity\User;
use App\Form\AddHorseFormType;
use App\Form\HorseFormType;
use App\Form\HorseRiderFormType;
use App\Form\RegisterHorseType;
use App\Form\SelectHorseFormType;
use App\Repository\EventRepository;
use App\Repository\HorseRepository;
use App\Repository\horseriderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HorseRiderController extends AbstractController
{
    /**
     * @Route("event{id}/horserider", name="horse_rider")
     * @param EventRepository $eventRepository
     * @param $id
     * @param Request $request
     * @ParamConverter("event", options={"mapping": {"id": "id"}})
     * @param Horserider $horserider
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Entity("event", expr="repository.find(id)")
     */
    public function index(EventRepository $eventRepository, $id, Request $request, Horserider $horserider = null, EntityManagerInterface $entityManager)
    {
        $horserider = new Horserider();
        $form = $this->createForm(AddHorseFormType::class);
        $form->handleRequest($request);

        $user = $this->getUser();
        $horses = $user->getHorses();
        $event = $eventRepository->find($id);

        $maxContestants = $event->getMaxContestants();
        $horseriders = $event->getHorseriders();

        if ($form->isSubmitted() && $form->isValid()) {
            $startNumber = count($horseriders) + 1;
            if ($startNumber <= $maxContestants) {
                $horseName = $form['name']->getData(); // string
                $horserider->setEvent($event);
                $horserider->setUser($user);
                $horserider->setHorse($horseName);
                $horserider->setStartNumber($startNumber);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($horserider);
                $entityManager->flush();

                return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
            } else {
                $this->addFlash(
                    'notice',
                    'This Event is already full'
                );
                return $this->redirectToRoute('horse_rider', ['id' => $event->getId()]);
            }
        }

        return $this->render('horse_rider/index.html.twig', [
            'horseForm' => $form->createView(),
            'user' => $user,
            'event' => $event,
            'horses' => $horses,
        ]);
    }
}
