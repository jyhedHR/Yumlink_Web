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



    #[Route('/average-calories-chart', name: 'admin_average_calories_chart', methods: ['GET'])]
    public function averageCaloriesChart(): Response
    {
        // Retrieve all calorie data from the database
        $allNutritionData = $this->getDoctrine()
            ->getRepository(UserNutrition::class)
            ->findAll();

        // Calculate the average of all calorie data
        $totalCalories = 0;
        $count = count($allNutritionData);
        foreach ($allNutritionData as $nutritionData) {
            $totalCalories += $nutritionData->getCalorie();
        }
        $averageCalories = $count > 0 ? $totalCalories / $count : 0;

        // Render the template, passing the average calorie consumption data
        return $this->render('user_nutrition/chart_admin.html.twig', [
            'averageCalories' => $averageCalories,
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
