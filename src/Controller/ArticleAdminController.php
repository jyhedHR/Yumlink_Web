<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/admin')]
class ArticleAdminController extends AbstractController
{
    #[Route('/listArticles', name: 'article_admin_list', methods: ['GET'])]
    public function listArticlesAdmin(ArticleRepository $articleRepository): Response
    {
        return $this->render('article_admin/postList.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/{idArticle}', name: 'article_delete_admin', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getIdArticle(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('article_admin_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/sortArticles/{sortType}/{columnName}', name: "sort_articles", methods: ["GET"])]
    public function sortArticles($sortType, $columnName, SerializerInterface $serializer, ArticleRepository $articleRepository): Response
    {
        if ($sortType  == 'asc') {
            $articles = $articleRepository->findByTitleOrder('ASC');
        } else {
            $articles = $articleRepository->findByTitleOrder('DESC');
        }
        
        $sortedArticlesJson = $serializer->serialize($articles, 'json');
        return new Response($sortedArticlesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
