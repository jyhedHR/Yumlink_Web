<?php

namespace App\Controller;

use App\Service\OcrService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OcrController extends AbstractController
{
    private $ocrService;

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    #[Route('/ocr', name: 'ocr', methods: ['GET', 'POST'])]
    public function ocr(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer le fichier téléchargé
            $uploadedFile = $request->files->get('image');
            
            // Vérifier si le fichier a été téléchargé
            if ($uploadedFile) {
                // Obtenir le chemin du fichier temporaire
                $filePath = $uploadedFile->getPathname();
                
                // Effectuer la reconnaissance de texte
                $text = $this->ocrService->recognizeText($filePath);
                
                // Afficher le texte reconnu
                return new Response("Texte reconnu: " . $text);
            }

            // Si aucun fichier n'a été téléchargé, renvoyer un message d'erreur
            return new Response("Veuillez télécharger une image pour effectuer la reconnaissance de texte.");
        }

        // Afficher le formulaire pour télécharger une image (GET request)
        return $this->render('ocr/index.html.twig');
    }
    #[Route('/upload-order', name: 'upload_order', methods: ['POST'])]
    public function uploadOrder(Request $request): Response
    {
        // Récupérer le fichier téléchargé
        $uploadedFile = $request->files->get('order_file');

        // Vérifier si un fichier a été téléchargé
        if ($uploadedFile) {
            // Obtenir le chemin du fichier temporaire
            $filePath = $uploadedFile->getPathname();
            
            // Effectuer la reconnaissance de texte sur le fichier
            $text = $this->ocrService->recognizeText($filePath);
            
            // Extraire les détails de la commande
            $orderDetails = $this->ocrService->extractOrderDetails($text);

            // Vous pouvez maintenant utiliser les détails de la commande extraits
            // pour traiter la commande dans votre système
            
            // Retourner une réponse indiquant le succès ou les détails extraits
            return new Response("Détails de la commande extraits avec succès !");
        }

        // Si aucun fichier n'a été téléchargé, renvoyer un message d'erreur
        return new Response("Veuillez télécharger un bon de commande ou un bordereau de livraison.");
    }
}


