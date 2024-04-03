<?php

// UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;  

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('mdp', TextType::class, [
                'label' => 'Mot de passe',
                'required' => true,
            ])
            ->add('tel')
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Client' => 'Client',
                    'Chef' => 'Chef',
                ],
                'expanded' => true, // Défini à true pour des boutons radios, false pour un menu déroulant
                'multiple' => false,
                'choice_attr' => [
                    'Client' => ['class' => 'radio-custom'],
                    'Chef' => ['class' => 'radio-custom'],
                ],
                'attr' => [
                    'class' => 'role-field',
                ],
            ])
            
            ->add('image', FileType::class, [
                'label' => 'Ajouter une photo de profil',
                'required' =>false, // Change to false if the image is not always required
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
           
            //->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

