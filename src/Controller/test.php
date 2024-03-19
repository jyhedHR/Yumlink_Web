<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
#[Route('/article')]
class test extends AbstractController
{
#[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'articles' => "article",
        ]);
    }
}