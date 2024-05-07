<?php

namespace App\Controller;

use App\Service\ChatGPTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatGPTController extends AbstractController
{
    private $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    #[Route('/chatt', name: 'chatt', methods: ['POST', 'GET'])]
    public function chat(Request $request): Response
    {
        $responseText = null;

        if ($request->isMethod('POST')) {
            $prompt = $request->request->get('prompt');

            $responseText = $this->chatGPTService->chat($prompt);
        }

        return $this->render('chat_gpt/index.html.twig', [
            'response' => $responseText,
        ]);
    }
}



