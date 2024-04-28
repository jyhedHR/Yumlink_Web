<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void   
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
