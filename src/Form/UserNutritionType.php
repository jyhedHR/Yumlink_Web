<?php

namespace App\Form;
use App\Entity\UserNutrition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserNutritionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('age')
        ->add('weight')
        ->add('height')
        ->add('activityLevel', ChoiceType::class, [
            'choices' => [
                'Lazy' => 'Lazy',
                'Active' => 'Active',
            ],
        ])
        ->add('gender', ChoiceType::class, [
            'choices' => [
                'Male' => 'Homme',
                'Female' => 'Femme',
            ],
        ])
          // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserNutrition::class,
        ]);
    }
}
