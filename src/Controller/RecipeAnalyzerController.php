<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHttp\Client;

class RecipeAnalyzerController extends AbstractController
{
    #[Route('/recipe/analyzer', name: 'app_recipe_analyzer')]
    public function index(): Response
    {
        return $this->render('recipe_analyzer/index.html.twig', [
            'controller_name' => 'RecipeAnalyzerController',
        ]);
    }

    
    #[Route('/recipe/analyze', name: 'app_analyzer', methods: ['GET', 'POST'])]
    public function analyze(Request $request): Response
    {
        $inputRecipe = $request->get('input');

        if (!empty($inputRecipe)) {
            $client = new Client([
                'base_uri' => 'https://api.edamam.com/api/nutrition-details?app_id=58aea65e&app_key=5a68e455d5051fbf5a1995c24d06e5c9',
                
            ]);

            $response = $client->post('', [
                'json' => [
                    'title' => 'Recipe Title',
                    'ingr' => [$inputRecipe],
                   
                ],
            ]);

            $jsonResponse = json_decode($response->getBody()->getContents(), true);

            return $this->render('RecipeAnalyzer/output.html.twig', [
                'response' => $jsonResponse,
            ]);
        }

        return $this->render('RecipeAnalyzer/input.html.twig');
    }
}
