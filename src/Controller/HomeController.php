<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Message;
use App\Entity\Adherent;
use App\Form\MessageType;
use App\Form\AdherentType;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Adherent $adh = null, Request $request, MailerInterface $mailer, Event $event = null): Response
    {
        $eventsParticuliers = $doctrine->getRepository(Event::class)->findPages(1);
        $messages = $doctrine->getRepository(Message::class)->findAll();

        $form = $this->createForm(AdherentType::class, $adh);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $adh = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($adh);
            $entityManager->flush();

            $email = (new Email())
            ->from('votresite@paroisse-saverne.fr')
            ->to('florian.leininger01@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Un nouvel adhérent vous a rejoint !')
            ->text('Une nouvelle adresse e-mail a été ajoutée à votre liste d\'adhérents.')
            ->html('<p>Une nouvelle adresse e-mail a été ajoutée à votre liste d\'adhérents.</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_home');
        }

        //on veut les evenenments prévus dans les 3 prochaines semaines (21 jours)
        $events = $doctrine->getRepository(Event::class)->findBy([], ['dateEvent' => 'ASC']);
        //on crée la date d'aujourd'hui
        $now = new \Datetime();
        $nextEvents = [];
        //on crée l'interval à prévoir
        $diffMax = new \DateInterval("P21D");
        //on vérifie pour chaque événement s'il est comprit entre aujourd'hui et aujourd'hui + 21 jours
        foreach ($events as $event) {
            if ($event->getDateEvent() >= $now && $event->getDateEvent() <= ($now->add($diffMax))) {
                //si c'est le cas on l'ajoute à la liste des event suivants, si non, on vérifie le prochain, etc.
                $nextEvents[] = $event;
            }
        }

        return $this->render('home/index.html.twig', [
            'messages' => $messages,
            'adhForm' => $form->createView(),
            'events' => $nextEvents,
            'eventsParticuliers' => $eventsParticuliers,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_message')]
    public function edit(Message $message, ManagerRegistry $doctrine, Request $request): Response
    {
        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        if($messageForm->isSubmitted() && $messageForm->isValid()) {
            $message = $messageForm->getData();
            $entityManager = $doctrine->getManager();
            //prepare
            $entityManager->persist($message);
            //insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        //vue pour afficher le formulaire d'ajout
        return $this->render('home/edit.html.twig', [
            'messageForm' => $messageForm->createView(),
            'edit' => $message->getId()
        ]);
        return $this->render('home/index.html.twig', [
        ]);
    }
}
