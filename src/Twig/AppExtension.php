<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getGlobals(): array
    {
        // Définissez la variable `textia` avec une valeur par défaut
        return [
            'textia' => 'valeur par défaut',
        ];
    }
}