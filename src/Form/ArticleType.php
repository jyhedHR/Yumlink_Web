<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_article', TextType::class, [
                'label' => 'Title*',
            ])
            ->add('img_article', FileType::class, [
                'label' => 'Upload Image*',
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/*',],
            ])
            ->add('description_article', TextareaType::class, [
                'label' => 'Content*',
            ])
            ->add('tags', HiddenType::class, [
                'data' => '',
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Submit Now',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
