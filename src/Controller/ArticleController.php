<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    #[Route('/listAdmin', name: 'article_test_list', methods: ['GET'])]
    public function listArticlesAdmin(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    #[Route('/', name: 'app_article_blog', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/blog_grid.html.twig', [
            'articles' => $articleRepository->findAll(),
            'articlesSide' => $articleRepository->findTopArticles(5),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        //temporary
        $userId = 39;
        $user = $entityManager->getReference(User::class, $userId);
        //
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('img_article')->getData();
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
                $article->setImgArticle('assets/images/' . $newFilename);
            }
            $formData = $form->getData();
            $article->setTitleArticle($formData['title_article']);
            $article->setDescriptionArticle($formData['description_article']);
            $tagsArray = json_decode($formData['tags'], true);
            $this->handleTags($tagsArray, $entityManager);
            $article->setTags(json_decode($formData['tags'], true));
            $article->setDatePublished(new DateTime());
            //temporary
            $article->setUser($user);
            //
            $article->setNbLikesArticle(0);

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_blog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/create_post.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    public function handleTags(array $tagsArray, EntityManagerInterface $entityManager)
    {
        $existingTags = $entityManager->getRepository(Tag::class)->findBy(['tagValue' => $tagsArray['tags']]);
        $existingTagsMap = [];
        foreach ($existingTags as $existingTag) {
            $existingTagsMap[$existingTag->getTagValue()] = $existingTag;
        }
        foreach ($tagsArray['tags'] as $tagValue) {
            if (isset($existingTagsMap[$tagValue])) {
                $existingTag = $existingTagsMap[$tagValue];
                $existingTag->setTagNbUsage($existingTag->getTagNbUsage() + 1);
            } else {
                $newTag = new Tag();
                $newTag->setTagValue($tagValue);
                $newTag->setTagNbUsage(1);
                $entityManager->persist($newTag);
            }
        }
    }

    #[Route('/{idArticle}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article, ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository): Response
    {
        $comments = $commentaireRepository->fetchComments($article->getIdArticle());
        $author = $article->getUser()->getNom();
        return $this->render('article/post_details.html.twig', [
            'article' => $article,
            'author' => $author,
            'comments' => $comments,
            'commentsCount' => count($comments),
        ]);
    }

    #[Route('/{idArticle}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //temporary
            $userId = 39;
            $user = $entityManager->getReference(User::class, $userId);
            $article->setUser($user);
            $tagsArray = [
                'tags' => ['healthy', 'diet']
            ];
            $article->setTags($tagsArray);
            $entityManager->flush();
            return $this->redirectToRoute('app_article_blog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{idArticle}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getIdArticle(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_blog', [], Response::HTTP_SEE_OTHER);
    }
}
