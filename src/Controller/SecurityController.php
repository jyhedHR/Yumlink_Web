<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Recettes;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('Welcome');
    }
    public function onLoginSuccess()
    {
         return $this->redirectToRoute('homeOn');
    }
    #[Route('/', name: 'homeOn')]
    public function homeOn(SecurityController $securityC, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Redirect to login if no user is authenticated
            return $this->redirectToRoute('app_login');
        } else
        if ($user->isBlocked()) { // Modifiez cette ligne
            // Afficher un message d'erreur
            $this->addFlash('error', "Désolé, l'administrateur vous a bloqué. Veuillez contacter le support.");
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_logout');
        }
        $roles = $user->getRoles();
        $role = $roles[0];


        switch ($role) {
            case 'Client':
                return $this->render('user/ClientHome.html.twig');
            case 'Chef':
                $id = $securityC->getUser()->getIdU();
                $user = $entityManager->getReference(User::class, $id);
                $Recipes = $entityManager->getRepository(Recettes::class)->findBy(['user' => $user]);
                $articles = $entityManager->getRepository(Article::class)->findAll();
                $articleBig = array_shift($articles);
                if (count($articles) > 3) {
                    $articles = array_slice($articles, 0, 3);
                }
                dump($articles);
                dump($Recipes);
                return $this->render('user/ChefHome.html.twig', [
                    'Recipes' => $Recipes,
                    'articles' => $articles,
                    'articleBig' => $articleBig,
                ]);
            case 'Admin':
                return $this->redirectToRoute('app_user_index');

            default:
                return $this->redirectToRoute('app_user_new');
                break;
        }
    }
    #[Route('/Welcome', name: 'Welcome')]
    public function Welcome(): Response
    {  return $this->render('WelcomeBase.html.twig');}
    
    #[Route(path: '/loginadmin', name: 'app_adminLogin')]
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security_admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
