<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
