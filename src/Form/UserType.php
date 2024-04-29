<?php

// UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;  

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
        ->add('role', ChoiceType::class, [
            'label' => 'Role',
            
            'choices' => [
                'Client' => 'Client',
                'Chef' => 'Chef',
            ],
            'expanded' => true,
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
            'required' => false,
            'mapped' => false,
            'attr' => [
                'accept' => 'image/*',
            ],
        ]) ;
        /*->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'signup',
            'locale' => 'de',
        ]);*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

