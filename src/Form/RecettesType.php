<?php



namespace App\Form;
use App\Entity\Recettes;
use App\Entity\Ingredient;
use App\Entity\FileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecettesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom' , TextType::class , [
            'required' => false , 
        ])
        ->add('chef', TextType::class , [
            'required' => false , 

        ])
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
                'required' => false,
            ],
            'placeholder' => 'Choose a category', 
            'required' => false,
        ])
        ->add('description' , TextType::class , [
            'required' => false , ])
        ->add('imgsrc' , FileType::class,[
            'label' => 'Image',
            'required' => false, 
        ])
        // ->add('rating', RatingType::class, [ 
        //     'label' => 'Rating'
        // ])
        ->add('calorie' , TextType::class , [
            'required' => false , ])
        ->add('protein' , TextType::class , [
            'required' => false , ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recettes::class,
        ]);
    }
}
