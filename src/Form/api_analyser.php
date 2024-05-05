<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class api_analyser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plat_choisie', TextType::class, [
                'label' => 'Enter Recipe:',
                'attr' => [
                    'placeholder' => 'Enter your recipe here',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a recipe name.']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Analyze',
            ]);
    }
}
