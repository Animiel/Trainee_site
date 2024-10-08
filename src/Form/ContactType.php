<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => 'Nom : ',
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'label' => 'Prénom : ',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet : ',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message : ',
            ])
            ->add('numero', TelType::class, [
                'required' => false,
                'label' => 'Numéro : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
