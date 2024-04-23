<?php

namespace App\Controller;

use App\Entity\UserNutrition;
use App\Repository\UserNutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface

#[Route('/admin/usernutrition')]
class AdminUserNutritionController extends AbstractController
{
    #[Route('/', name: 'admin_user_nutrition_index', methods: ['GET'])]
    public function index(UserNutritionRepository $userNutritionRepository): Response
     {
        
        return $this->render('user_nutrition/admin_index.html.twig', [
            'user_nutritions' => $userNutritionRepository->findAll(),
         ]);
    }

    #[Route('/{user}', name: 'admin_user_nutrition_show', methods: ['GET'])]
    public function show(UserNutrition $userNutrition): Response
    {
        return $this->render('user_nutrition/show_user.html.twig', [
            'user_nutrition' => $userNutrition,
        ]);
    }


}
