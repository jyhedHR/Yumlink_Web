<?php

namespace App\Form;

use App\Entity\Recettes;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class RecettesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('chef')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom', 
                'multiple' => true,
            ])
            ->add('categorie')
            ->add('description')
            ->add('imgsrc')
            ->add('calorie')
            ->add('protein')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
        ]);
    }
}
