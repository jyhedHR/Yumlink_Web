<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag')]
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }

    #[Route('/fetchTagSuggestions', name: 'fetch_tag_suggestions', methods: ['GET'])]
    public function fetchTagSuggestions(TagRepository $tagRepository): JsonResponse
    {
        $tags = $tagRepository->findAll();
        $tagValues = [];
        foreach ($tags as $tag) {
            $tagValues[] = $tag->getTagValue();
        }
        return new JsonResponse($tagValues);
    }
}
