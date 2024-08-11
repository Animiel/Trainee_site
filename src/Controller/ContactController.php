<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine, Contact $contact = null, Request $request, MailerInterface $mailer): Response
    {
        if (!$contact) {
            $contact = new Contact();
        }
        
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $email = (new Email())
            ->from($contact->getEmail())
            ->to('florian.leininger01@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($contact->getSujet())
            ->text($contact->getMessage())
            ->html('<p>'.$contact->getMessage().'</p>');

            $mailer->send($email);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
