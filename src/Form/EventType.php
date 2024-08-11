<?php

namespace App\Form;

use App\Entity\Page;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé : '
            ])
            ->add('dateEvent', DateTimeType::class, [
                'label' => 'Date prévue : ',
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin : ',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu : '
            ])
            ->add('description', TextType::class, [
                'label' => 'Description : ',
                'required' => false
            ])
            ->add('image', FileType::class, [
                'label' => 'Image : ',
                'required' => false
            ])
            ->add('bgColor', ColorType::class, [
                'label' => 'Couleur de fond : ',
                'required' => true,
            ])
            ->add('textColor', ColorType::class, [
                'label' => 'Couleur de texte : ',
                'required' => true,
            ])
            ->add('allDay', ChoiceType::class, [
                'label' => 'L\' événement dure toute la journée ? ',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('pages', EntityType::class, [
                'class' => Page::class,
                'choice_label' => 'page',
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
