<?php

namespace App\Controller;

use App\Service\StatistiquesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiquesController extends AbstractController
{
    private $statistiquesService;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }

    #[Route('/statistiques', name: 'statistiques')]
    public function index(): Response
    {
        // Utilisez le service pour calculer les statistiques
        $stats = $this->statistiquesService->calculerStatistiques();

        // Affichez les statistiques dans une vue Twig
        return $this->render('statistiques/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}
