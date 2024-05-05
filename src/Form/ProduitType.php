<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control', 'placeholder' => 'Johnny Brown'],
            'constraints' => [
                new NotBlank(['message' => '']),
            ],
        ])
        ->add('prix', NumberType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control', 'placeholder' => '100', 'type' => 'number'],
            'constraints' => [
                new NotBlank(['message' => '']),
            ],
        ])
        ->add('diescription', TextareaType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter description'],
            'constraints' => [
                new NotBlank(['message' => '']),
            ],
        ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image', 
                'mapped' => false, 
                'required' => false,
                'attr' => [
                    'accept' => 'image/*', 
                    'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
