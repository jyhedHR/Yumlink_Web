<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Recettes;
use App\Entity\User;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\StatistiquesService;

class SecurityController extends AbstractController
{

    private $statistiquesService;

    public function __construct(StatistiquesService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }
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
                $recettes = $entityManager->getRepository(Recettes::class)->findAll();
                $articles = $entityManager->getRepository(Article::class)->findAll();
                $articleBig = array_shift($articles);
                if (count($articles) > 3) {
                    $articles = array_slice($articles, 0, 3);
                }
                if (count($recettes) > 12) {
                    $recettess = array_slice($recettes, 0, 12);
                }
                dump($articles);
                dump($recettes);
                return $this->render('user/ClientHome.html.twig', [
                    'recettes' => $recettess,
                    'articles' => $articles,
                    'articleBig' => $articleBig,
                ]);

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
    {
        return $this->render('WelcomeBase.html.twig');
    }
    #[Route('/ClientHome', name: 'ClientHome')]
    public function ClientHome(EntityManagerInterface $entityManager): Response
    {
        $recettes = $entityManager->getRepository(Recettes::class)->findAll();
        $articles = $entityManager->getRepository(Article::class)->findAll();
        $articleBig = array_shift($articles);
        if (count($articles) > 3) {
            $articles = array_slice($articles, 0, 3);
        }
        if (count($recettes) > 12) {
            $recettess = array_slice($recettes, 0, 12);
        }
        dump($articles);
        dump($recettes);
        return $this->render('user/ClientHome.html.twig', [
            'recettes' => $recettess,
            'articles' => $articles,
            'articleBig' => $articleBig,
        ]);
    }
    #[Route('/ChefHome', name: 'ChefHome')]
    public function ChefHome(EntityManagerInterface $entityManager, SecurityController $securityC): Response
    {
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
    }
    #[Route('/ClientAdmin', name: 'ClientAdmin')]
    public function ClientAdmin(EntityManagerInterface $entityManager, RecettesRepository $recettesRepository): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Compter les utilisateurs par rôle
        $rolesCount = [];
        foreach ($users as $user) {
            $role = $user->getRole();
            if (!isset($rolesCount[$role])) {
                $rolesCount[$role] = 1;
            } else {
                $rolesCount[$role]++;
            }
        }
        $stats = $this->statistiquesService->calculerStatistiques();

        // Affichez les statistiques dans une vue Twig



        // Calculer les pourcentages de chaque rôle
        $totalUsers = count($users);
        $rolePercentages = [];
        foreach ($rolesCount as $role => $count) {
            $percentage = ($count / $totalUsers) * 100;
            $rolePercentages[$role] = $percentage;
        }


        // Retrieve data for Recipes Per Category chart
        $recipesPerCategory = $entityManager->getRepository(Recettes::class)->getRecipesPerCategory();
        $labelsPerCategory = [];
        $dataPerCategory = [];
        foreach ($recipesPerCategory as $row) {
            $labelsPerCategory[] = $row['category'];
            $dataPerCategory[] = $row['recipe_count'];
        }

        // Retrieve data for Activity Line chart
        $activityData = $recettesRepository->countRecipesByMonth();
        $monthsActivity = [];
        $recipeCountsActivity = [];
        for ($i = 1; $i <= 12; $i++) { // Loop through months 1 to 12
            $monthsActivity[] = $i;
            $recipeCountsActivity[] = $activityData[$i] ?? 0; // Use 0 if month data is not available
        }

        // Retrieve data for Most Popular Chef chart
        $popularChefsData = $recettesRepository->getMostPopularChefs();
        $labelsPopularChefs = [];
        $dataPopularChefs = [];
        foreach ($popularChefsData as $row) {
            $labelsPopularChefs[] = $row['chef_name'];
            $dataPopularChefs[] = $row['recipe_count'];
        }

        // Render the chart template with the data
        return $this->render('homeadmin.html.twig', [
            'labelsPerCategory' => json_encode($labelsPerCategory),
            'dataPerCategory' => json_encode($dataPerCategory),
            'monthsActivity' => json_encode($monthsActivity),
            'recipeCountsActivity' => json_encode($recipeCountsActivity),
            'labelsPopularChefs' => json_encode($labelsPopularChefs),
            'dataPopularChefs' => json_encode($dataPopularChefs),
            'rolePercentages' => $rolePercentages,
            'stats' => $stats,
        ]);
    }

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
