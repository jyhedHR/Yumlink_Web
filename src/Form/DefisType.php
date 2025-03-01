<?php

namespace App\Form;

use App\Entity\Defis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class DefisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomD')
            ->add('photoD', FileType::class, [
                'label' => 'Image', // Set label for the file input
                'mapped' => false, // This field is not mapped to any property of your entity
                'required' => false, // Not required, since image upload is optional
                'attr' => [
                    'accept' => 'image/*', // Specify accepted file types (images)
                    // Use JavaScript to display the selected file name in a separate label
                    'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
                ],])
            ->add('disD')
            ->add('delai')
            ->add('heure')
            ->add('user', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Defis::class,
        ]);
    }
}
