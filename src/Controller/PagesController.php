<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Photo;
use App\Form\PhotoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findAll();
        $eventsLong = $doctrine->getRepository(Event::class)->findLong();
        $jsonEvents = [];

        foreach ($events as $event) {
            if($event->getImage() != null && $event->getDateFin() != null) {
                $jsonEvents[] = [
                    'id' => $event->getId(),
                    'title' => $event->getIntitule(),
                    'start' => $event->getDateEvent()->format("Y-m-d H:i:s"),
                    'end' => $event->getDateFin()->format("Y-m-d H:i:s"),
                    'backgroundColor' => $event->getBgColor(),
                    'textColor' => $event->getTextColor(),
                    'borderColor' => $event->getBorderColor(),
                    // 'allDay' => $event->isAllDay(),
                    'lieu' => $event->getLieu(),
                    'description' => $event->getDescription(),
                    'image' => $event->getImage(),
                ];
            }
            else if($event->getImage() != null) {
                $jsonEvents[] = [
                    'id' => $event->getId(),
                    'title' => $event->getIntitule(),
                    'start' => $event->getDateEvent()->format("Y-m-d H:i:s"),
                    'backgroundColor' => $event->getBgColor(),
                    'textColor' => $event->getTextColor(),
                    'borderColor' => $event->getBorderColor(),
                    // 'allDay' => $event->isAllDay(),
                    'lieu' => $event->getLieu(),
                    'description' => $event->getDescription(),
                    'image' => $event->getImage(),
                ];
            }
            else if($event->getDateFin() != null) {
                $jsonEvents[] = [
                    'id' => $event->getId(),
                    'title' => $event->getIntitule(),
                    'start' => $event->getDateEvent()->format("Y-m-d H:i:s"),
                    'end' => $event->getDateFin()->format("Y-m-d H:i:s"),
                    'backgroundColor' => $event->getBgColor(),
                    'textColor' => $event->getTextColor(),
                    'borderColor' => $event->getBorderColor(),
                    // 'allDay' => $event->isAllDay(),
                    'lieu' => $event->getLieu(),
                    'description' => $event->getDescription(),
                ];
            }
            else {
                $jsonEvents[] = [
                    'id' => $event->getId(),
                    'title' => $event->getIntitule(),
                    'start' => $event->getDateEvent()->format("Y-m-d H:i:s"),
                    'backgroundColor' => $event->getBgColor(),
                    'textColor' => $event->getTextColor(),
                    'borderColor' => $event->getBorderColor(),
                    // 'allDay' => $event->isAllDay(),
                    'lieu' => $event->getLieu(),
                    'description' => $event->getDescription(),
                ];
            }
        }

        $data = json_encode($jsonEvents);

        foreach ($eventsLong as $event) {
            $now = new \Datetime();
            $month = date('m');

            // if($event->getDateEvent()->date("m") === $month || $event->getDateFin()->date("m") === $month) {
            //     $eventsLong[] = $event;
            // }
        }

        return $this->render('pages/agenda.html.twig', compact('data'));
    }

    #[Route('/eventEdition', name: 'event_edition')]
    public function eventEdition(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findBy([], ['dateEvent' => 'DESC']);
        $now = new \Datetime();

        return $this->render('pages/eventEdition.html.twig', [
            'events' => $events,
            'now' => $now,
        ]);
    }

    #[Route('/enfants&ados', name: 'app_ados')]
    public function ados(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findPages(3);
        return $this->render('pages/ados.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/souvrirauxautres', name: 'app_souvrir')]
    public function souvrir(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findPages(4);
        return $this->render('pages/souvrir.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/pratique', name: 'app_pratique')]
    public function pratique(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findPages(6);
        return $this->render('pages/pratique.html.twig', [
            'events' => $events,
        ]);
    }
}
