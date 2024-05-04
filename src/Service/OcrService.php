<?php
namespace App\Service;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function recognizeText(string $imagePath): string
    {
        $text = (new TesseractOCR($imagePath))
            ->lang('eng') // Spécifiez la langue de reconnaissance
            ->run();

        return $text;
    }
    public function extractOrderDetails(string $text): array
    {
        // Logique pour extraire les détails des commandes à partir du texte reconnu
        // Vous pouvez utiliser des expressions régulières ou d'autres techniques pour extraire les informations pertinentes
        
        // Exemple d'extraction de détails de commande
        $details = [
            'order_id' => null,
            'date' => null,
            'items' => [],
            // Ajoutez d'autres détails que vous souhaitez extraire
        ];

        // Exemple d'utilisation d'une expression régulière pour extraire l'ID de commande
        if (preg_match('/Order ID: (\d+)/', $text, $matches)) {
            $details['order_id'] = $matches[1];
        }

        // Ajoutez d'autres extractions d'informations ici

        return $details;
    }
}