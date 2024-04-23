<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class MealPlanController extends AbstractController
{
    #[Route('/meal-plan', name: 'meal_plan')]
    public function mealPlan(Request $request): Response
    {
        $targetCalories = 2500;

        $apiKey = 'ad7fd16c15b847aaab49f39ab9d159dd';
        $timeFrame = 'day';

        $client = new Client();
        $url = "https://api.spoonacular.com/mealplanner/generate?apiKey={$apiKey}&timeFrame={$timeFrame}&targetCalories={$targetCalories}";
        $response = $client->get($url);
        $mealPlan = json_decode($response->getBody(), true);

        $totalCalories = $mealPlan['nutrients']['calories'];

        return $this->render('user_nutrition/meal_plan.html.twig', [
            'meals' => $mealPlan['meals'],
            'totalCalories' => $totalCalories,
        ]);
    }
}