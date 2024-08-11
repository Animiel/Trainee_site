<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    #[Route('/photo', name: 'app_photo')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $photos = $doctrine->getRepository(Photo::class)->findAll();

        return $this->render('photo/index.html.twig', [
            'photos' => $photos,
        ]);
    }

    #[Route('/newphoto', name: 'add_photo')]
    #[Route('/modifyphoto/{id}', name: 'mod_photo')]
    public function photos(ManagerRegistry $doctrine, Photo $photo = null, Request $request): Response
    {
        if (!$photo){
            $photo = new Photo();
        }

        // if($photo) {
        //     $photo->setPhoto(null);
        // }

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $photo = $form->getData();
            $image = $form->get('photo')->getData();

            if($image) {
                $file = uniqid().'.'.$image->guessExtension();
                $photo->setPhoto($file);
                $image->move('img/photos', $file);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
        }

        return $this->render('photo/form.html.twig', [
            'photoForm' => $form,
        ]);
        return $this->redirectToRoute('app_photo');
    }

    #[Route('/supprphoto/{id}', name: 'suppr_photo')]
    public function delete(ManagerRegistry $doctrine, Photo $photo)
    {
        $em = $doctrine->getManager();
        $em->remove($photo);
        $em->flush();
        return $this->redirectToRoute('app_photo');
    }
}
