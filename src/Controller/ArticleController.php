<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\PostLikes;
use App\Entity\User;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
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

    #[Route('/{idArticle}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository,  Request $request): Response
    {
        $isLikedByCurrentUser = false;
        $comments = $commentaireRepository->fetchComments($article->getIdArticle());
        $author = $article->getUser()->getNom();
        //temporary
        $userId = 39;
        $user = $entityManager->getReference(User::class, $userId);
        //
        $liked = $entityManager->getRepository(PostLikes::class)->findOneBy(['article' => $article, 'user' => $user]);
        if ($liked !== null) {
            dump($liked);
            $isLikedByCurrentUser = true;
        } else {
            $isLikedByCurrentUser = false;
        }
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $commentaire->setCommentText($formData['commentText']);
            $commentaire->setCommentDate(new DateTime());
            //temporary
            $userId = 39;
            $user = $entityManager->getReference(User::class, $userId);
            //
            $commentaire->setUser($user);
            $commentaire->setIdArticle($article->getIdArticle());
            $entityManager->persist($commentaire);
            $entityManager->flush();
            dump($commentaire);
            return $this->redirectToRoute('app_article_show', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('article/post_details.html.twig', [
            'article' => $article,
            'author' => $author,
            'comments' => $comments,
            'commentsCount' => count($comments),
            'isLikedByCurrentUser' => $isLikedByCurrentUser,
            //
            'commentaire' => $commentaire,
            'form' => $form->createView(),
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
            if ($tagsArray != null) { 
                $this->handleTags($tagsArray, $entityManager);    
                $article->setTags(json_decode($formData['tags'], true));
            }
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

    #[Route('/articles/{id}/like', name: "like_article", methods: ['POST'])]
    public function likeArticle(EntityManagerInterface $entityManager, Article $article): JsonResponse
    {
        //temporary
        $userId = 39;
        $user = $entityManager->getReference(User::class, $userId);
        //
        $liked = $entityManager->getRepository(PostLikes::class)->findOneBy(['article' => $article, 'user' => $user]);

        if ($liked) {
            return new JsonResponse(['error' => 'Already liked'], 400);
        }
        $postLike = new PostLikes();
        $postLike->setArticle($article);
        $postLike->setUser($user);

        $entityManager->persist($postLike);
        $entityManager->flush();

        $article->incrementLikes();
        $entityManager->persist($article);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Article liked', 'likes' => $article->getNbLikesArticle()]);
    }

    #[Route('/articles/{id}/dislike', name: "dislike_article", methods: ['POST'])]
    public function unlikeArticle(EntityManagerInterface $entityManager, Article $article): JsonResponse
    {
        //temporary
        $userId = 39;
        $user = $entityManager->getReference(User::class, $userId);
        //
        $liked = $entityManager->getRepository(PostLikes::class)->findOneBy(['article' => $article, 'user' => $user]);

        if (!$liked) {
            return new JsonResponse(['error' => 'Not liked'], 400);
        }

        $entityManager->remove($liked);
        $entityManager->flush();

        $article->decrementLikes();
        $entityManager->persist($article);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Article disliked', 'likes' => $article->getNbLikesArticle()]);
    }
}
