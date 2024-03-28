<?php

namespace App\Form;

use App\Entity\Recettes;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'mapped' => false, 
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Dinner' => 'Dinner',
                    'Lunch' => 'Lunch',
                    'Dessert' => 'Dessert',
                    'Breakfast' => 'Breakfast',
                ],
                'placeholder' => 'Choose a category', 
                'required' => true,
            ])
            ->add('description')
            ->add('imgsrc' , FileType::class,[
                'label' => 'Image',
                'required' => false, 
            ])
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
