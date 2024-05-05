<?php

namespace App\Controller;

use App\Entity\UserNutrition;
use App\Entity\Recettes;
use App\Repository\RecettesRepository ;
use App\Repository\UserNutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecommendationController extends AbstractController
{
    #[Route('/recommendations', name: 'recommendations')]
    public function recommendations(UserNutritionRepository $userNutritionRepository, RecettesRepository $recettesRepository): Response // Corrected argument name
    {
        $user = $this->getUser(); // Assuming Symfony's built-in security is used to retrieve the current user

        $userNutrition = $userNutritionRepository->findOneBy(['user' => $user->getIdu()]);
    

        // Check if user nutrition data exists
        if (!$userNutrition) {
        }

        // Get the calorie intake from user nutrition data
        $calorieIntake = $userNutrition->getCalorie();

        // Fetch recommendations of recipes based on calorie intake
        $recommendations = $recettesRepository->getRecettesUnderCalorieThreshold($calorieIntake);

        // Check if there are recommendations
        if ($recommendations) {
            // Insert recommendations into the nutrition_recommandation table
            $recettesRepository->insertRecommendations($recommendations, $user->getIdu()); // Use current user's ID
        }

        // Render the view with the recommendations
        return $this->render('user_nutrition/recommendations.html.twig', [
            'recommendations' => $recommendations,
        ]);
    }
}
