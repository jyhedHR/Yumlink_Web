<?php

namespace App\Controller;

use App\Form\api_analyser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

class RecipeAnalyzerController extends AbstractController
{
    private $httpClient;
    private $apiEndpoint = 'https://api.edamam.com/api/nutrition-data';
    private $appId = '392aa5ca';
    private $appKey = '09805aee9b2412db87de2907891136ed';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/get-regime', name: 'get_regime', methods: ['GET', 'POST'])]
    public function getRegime(Request $request): Response
    {
        $form = $this->createForm(api_analyser::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $pc = $data['plat_choisie'];
    
            // Prepare request data
         $requestData = [
    'query' => [
        'app_id' => $this->appId,
        'app_key' => $this->appKey,
        'nutrition-type' => 'cooking',
        'ingr' => $pc, // Ensure that $pc contains the correct value
    ],
];
    
            // Send request to Edamam API
            try {
                $response = $this->httpClient->request('GET', $this->apiEndpoint, $requestData);
                $statusCode = $response->getStatusCode();
                $content = $response->toArray();
    
                if ($statusCode === 200) {
                    // Extract the nutrient values
                    $totalNutrients = $content['totalNutrients'];
                    $calories = $totalNutrients['ENERC_KCAL']['quantity'];
                    $protein = $totalNutrients['PROCNT']['quantity'];
                    $fats = $totalNutrients['FAT']['quantity'];
                    $carbs = $totalNutrients['CHOCDF']['quantity'];
    
                    // Return the nutrient values as JSON response
                    return new JsonResponse([
                        'calories' => $calories,
                        'protein' => $protein,
                        'fats' => $fats,
                        'carbs' => $carbs,
                    ]);
                } else {
                    return new JsonResponse(['error' => 'Error: Unable to analyze recipe. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } catch (ClientException $e) {
                // Handle client-side errors (4xx)
                return new JsonResponse(['error' => 'Client Error: ' . $e->getMessage()], $e->getCode());
            } catch (\Exception $e) {
                // Handle other errors
                return new JsonResponse(['error' => 'Error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    
        // If the form is not submitted, render the form view
        return $this->render('user_nutrition/analyser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
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
