<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FoodvisorController extends AbstractController
{
    #[Route('/foodvisor', name: 'app_foodvisor')]
    public function index(): Response
    {
        return $this->render('foodvisor/index.html.twig', [
            'controller_name' => 'FoodvisorController',
        ]);
    }


    public function analyzeImage(Request $request): Response
    {
        // Get the image file from the request
        $imageFile = $request->files->get('image');

        // Check if an image file was uploaded
        if (!$imageFile) {
            return new Response('No image file provided', Response::HTTP_BAD_REQUEST);
        }

        // Configure API endpoint and headers
        $url = "https://vision.foodvisor.io/api/1.0/en/analysis/";
        $apiKey = "0R0yKt01.sdFNqq3iIhi1eZvXddYF0GVvP2PlvAUx";
        $headers = ["Authorization" => "Api-Key $apiKey"];

        // Send POST request to Foodvisor API
        $response = $this->sendImageToFoodvisor($url, $headers, $imageFile);

        // Handle response
        if ($response->getStatusCode() === Response::HTTP_OK) {
            $data = $response->toArray(); // Convert JSON response to array
            // Process the response data as needed
            return $this->json($data);
        } else {
            // Handle error response
            return new Response('Error analyzing image', $response->getStatusCode());
        }
    }

    private function sendImageToFoodvisor(string $url, array $headers, $imageFile): Response
    {
        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        // Send POST request with image file
        $response = $client->post($url, [
            'headers' => $headers,
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen($imageFile->getPathname(), 'r'), // Open file stream
                    'filename' => $imageFile->getClientOriginalName(), // Use original file name
                ],
            ],
        ]);

        return new Response($response->getBody(), $response->getStatusCode());
    }



}
