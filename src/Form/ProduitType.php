<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('diescription')
            ->add('imageFile', FileType::class, [
                'label' => 'Image', // Set label for the file input
                'mapped' => false, // This field is not mapped to any property of your entity
                'required' => false, // Not required, since image upload is optional
                'attr' => [
                    'accept' => 'image/*', // Specify accepted file types (images)
                    // Use JavaScript to display the selected file name in a separate label
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
