<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeAnalyzerController extends AbstractController
{
    #[Route('/recipe/analyzer', name: 'app_recipe_analyzer')]
    public function index(): Response
    {
        return $this->render('recipe_analyzer/index.html.twig', [
            'controller_name' => 'RecipeAnalyzerController',
        ]);
    }

    private const API_KEY = 'e3478e9f73b116663d0d8782874b2f56';
    #[Route('/analyze', name: 'app_analyzer', methods: ['GET', 'POST'])]
    public function analyze(Request $request): Response
    {
        $inputRecipe = $request->get('input');

        if (!empty($inputRecipe)) {
            $client = new Client([
                'base_uri' => 'https://api.edamam.com/api/nutrition-details',
            ]);

            $response = $client->post('', [
                'json' => [
                    'title' => 'Recipe Title',
                    'ingr' => [$inputRecipe],
                    'app_id' => '58aea65e', // This should be your Edamam API app ID
                    'app_key' => self::API_KEY,
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
