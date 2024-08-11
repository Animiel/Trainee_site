<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label' => 'Images : ',
                'multiple' => false,
                'data_class' => null,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Légende / Description de l\'image : ',
            ])
            ->add('lien', UrlType::class, [
                'required' => false,
                'label' => 'Lien vers l\'événement : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
