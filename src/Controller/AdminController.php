<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Adherent;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/adherents', name: 'list_adherents')]
    public function listeAdherents(ManagerRegistry $doctrine): Response
    {
        $adherents = $doctrine->getRepository(Adherent::class)->findAll();

        return $this->render('admin/adherents.html.twig', [
            'adherents' => $adherents,
        ]);
    }

    #[Route('/event', name: 'new_event')]
    #[Route('/event/edit/{id}', name: 'edit_event')]
    public function createEvent(ManagerRegistry $doctrine, Event $event = null, Request $request): Response
    {
        if (!$event) {
            $event = new Event();
        }
        
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $image = $form->get('image')->getData();
            $allDay = $form->get('allDay')->getData();
            $dateEvent = $form->get('dateEvent')->getData();
            $dateEndEvent = $form->get('dateFin')->getData();

            $dateDayEvent = $dateEvent->format('Y-m-d');

            if($image) {
                $file = uniqid().'.'.$image->guessExtension();
                $event->setImage($file);
                $image->move('img/event', $file);
            }
            $event->setBorderColor("#000000");
            if($allDay) {
                if($allDay === "Oui") {
                    $event->setAllDay(true);
                    $newDate = \DateTime::createFromFormat('Y-m-d H:i:s', $dateDayEvent."00:00:00")->format('Y-m-d H:i:s');
                    $newEnd = \DateTime::createFromFormat('Y-m-d H:i:s', $dateDayEvent."23:59:59")->format('Y-m-d H:i:s');
                    $event->setDateEvent(new \DateTime($newDate));
                    $event->setDateFin(new \DateTime($newEnd));
                }
                else {
                    $event->setAllDay(false);
                    $event->setDateEvent($dateEvent);
                    $event->setDateFin($dateEndEvent);
                }
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_agenda');
        }

        return $this->render('admin/createEvent.html.twig', [
            'eventForm' => $form->createView()
        ]);
    }

    #[Route('/event/suppr/{id}', name: 'suppr_event')]
    public function delete(ManagerRegistry $doctrine, Event $event)
    {
        $em = $doctrine->getManager();
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('event_edition');
    }
}
