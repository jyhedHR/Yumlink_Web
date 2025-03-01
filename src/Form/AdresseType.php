<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3; 

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('gouvernorat', TextType::class, [
            'attr' => [
                'id' => 'gouv',
                'name' => 'gouvernorat',
                'readonly' => false ,
                'required' => false,
            ]])
            ->add('ville', TextType::class, [
                'attr' => [
                    'id' => 'ville',
                    'name' => 'ville',
                    'readonly' => false,
                    'required' => false,
                ],
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'id' => 'rue',
                    'name' => 'rue',
                    'readonly' => false,
                    'required' => false,
                ],
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'id' => 'code_postal',
                    'name' => 'code_postal',
                    'readonly' => false,
                    'required' => false,
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'Adresse',
                'locale' => 'de',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void   
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
