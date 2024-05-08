<?php

namespace App\Controller;

use App\Form\ImageAPIType;
use Exception;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyseAPIController extends AbstractController
{
    #[Route('/apiVisor/recipe/{image?}', name: 'analyse_api', methods: ['GET', 'POST'], defaults: ['image' => null])]
    public function analyse($image, Request $request): Response
    {
        $form = $this->createForm(ImageAPIType::class, null);
        $form->handleRequest($request);
        $image = '';
        $path = ('frontend/assets/images/diet-plan/diet-plan1.jpg');
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photoD')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_produit_new');
                }
                $path = ('frontend/assets/images/' . $newFilename);
            }
            $data = $this->analysePictureAPI($path);
            $namePlate = $data[0];
            $ingredientsNames = array_slice($data, 1);
            $gr_serving = array_slice($data, 2);
            $min_length = min(count($ingredientsNames), count($gr_serving));
            for ($i = 0; $i < $min_length; $i+=2) {
                $ingredientsAndServings[] = [
                    'ingredient' => $ingredientsNames[$i],
                    'serving' => $gr_serving[$i]
                ];
            }

            return $this->renderForm('analyse_api/api.html.twig', [
                'form' => $form,
                'imageUploaded' => $path,
                'namePlate' => $namePlate,
                'ingredientsAndServings' => $ingredientsAndServings,
            ]);
        }
        return $this->renderForm('analyse_api/api.html.twig', [
            'form' => $form,
            'imageUploaded' => $image,
            'namePlate' => 'Upload your image and check it out',
            'ingredientsAndServings' => 'analyse',
        ]);
    }

    public function analysePictureAPI($path)
    {
        $client = new Client();
        $url = "https://vision.foodvisor.io/api/1.0/en/analysis/";
        $headers = [
            "Authorization" => "Api-Key 2Ypk2YL0.pzNYDViH2PERkMAERUgvoAfCVF2u9wPT"
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($path, 'r'),
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $namePlate = $data['items'][0]['food'][0]['food_info']['display_name'];
            $ingredientsNames = array_map(function ($item) {
                return $item['food_info']['display_name'];
            }, $data['items'][0]['food']);
            $gr_serving = array_map(function ($item) {
                return $item['food_info']['g_per_serving'] . " g";
            }, $data['items'][0]['food']);
            dump($namePlate, $ingredientsNames);
            $combinedArray = array_merge([$namePlate], $ingredientsNames, $gr_serving);
            return $combinedArray;
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
