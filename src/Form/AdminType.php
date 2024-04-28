<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'required' => false,
        ])
        ->add('prenom', TextType::class, [
            'required' => false,
        ])
        ->add('email', TextType::class, [
            'required' => false,
        ])
        ->add('mdp', TextType::class, [
            'label' => 'Mot de passe',
            'required' => false,
        ])
        ->add('tel', TextType::class, [
            'required' => false,
        ])
        ->add('image', FileType::class, [
            'label' => 'Ajouter une photo de profil',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'accept' => 'image/*',
            ],
        ])
        ->add('role', HiddenType::class, [
            'data' => 'Admin',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
