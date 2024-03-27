<?php

namespace App\Controller;

use App\Entity\UserNutrition;
use App\Form\UserNutritionType;
use App\Repository\UserNutritionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nutrition')]
class UserNutritionController extends AbstractController
{
    #[Route('/', name: 'app_user_nutrition_index', methods: ['GET'])]
    public function index(UserNutritionRepository $userNutritionRepository): Response
    {
        return $this->render('user_nutrition/index.html.twig', [
            'user_nutritions' => $userNutritionRepository->findAll(),
        ]);
    }




    #[Route('/new', name: 'app_user_nutrition_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $userNutrition = new UserNutrition();
    $form = $this->createForm(UserNutritionType::class, $userNutrition);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Get user input
        $age = $form->get('age')->getData();
        $weight = $form->get('weight')->getData();
        $height = $form->get('height')->getData();
        $activityLevel = $form->get('activityLevel')->getData();
        $gender = $form->get('gender')->getData();

        // Calculate TDEE
        $bmr = ($gender === 'Homme') ? (10 * $weight + 6.25 * $height - 5 * $age + 5) : (10 * $weight + 6.25 * $height - 5 * $age - 161);
        $activityFactors = [
            'Lazy' => 1.2,
            'Active' => 1.9
        ];
        $activityFactor = $activityFactors[$activityLevel];
        $calorie = $bmr * $activityFactor;

        // Distribute macronutrients
        $proteinRatio = 0.3; // 30%
        $carbRatio = 0.4; // 40%
        $fatRatio = 0.3; // 30%
        $proteinCalories = $calorie * $proteinRatio;
        $carbCalories = $calorie * $carbRatio;
        $fatCalories = $calorie * $fatRatio;
        $protein = $proteinCalories / 4;
        $carbs = $carbCalories / 4;
        $fat = $fatCalories / 9;

        // Update entity with calculated values
        $userNutrition->setCalorie($calorie);
        $userNutrition->setProtein($protein);
        $userNutrition->setCarbs($carbs);
        $userNutrition->setFat($fat);

        // Persist and flush changes to the database
        $entityManager->persist($userNutrition);
        $entityManager->flush();

        // Redirect to index page or any other page as needed
        return $this->redirectToRoute('app_user_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }

    // Render the form if not submitted or invalid
    return $this->renderForm('user_nutrition/new.html.twig', [
        'user_nutrition' => $userNutrition,
        'form' => $form,
    ]);
}




    
    #[Route('/{user}/edit', name: 'app_user_nutrition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserNutrition $userNutrition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserNutritionType::class, $userNutrition);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get user input
            $age = $form->get('age')->getData();
            $weight = $form->get('weight')->getData();
            $height = $form->get('height')->getData();
            $activityLevel = $form->get('activityLevel')->getData();
            $gender = $form->get('gender')->getData();
    
            // Calculate TDEE
            $bmr = ($gender === 'Homme') ? (10 * $weight + 6.25 * $height - 5 * $age + 5) : (10 * $weight + 6.25 * $height - 5 * $age - 161);
            $activityFactors = [
                'Lazy' => 1.2,
                
                'Active' => 1.9
            ];
            $activityFactor = $activityFactors[$activityLevel];
            $calorie = $bmr * $activityFactor;
    
            // Distribute macronutrients
            $proteinRatio = 0.3; // 30%
            $carbRatio = 0.4; // 40%
            $fatRatio = 0.3; // 30%
            $proteinCalories = $calorie * $proteinRatio;
            $carbCalories = $calorie * $carbRatio;
            $fatCalories = $calorie * $fatRatio;
            $protein = $proteinCalories / 4;
            $carbs = $carbCalories / 4;
            $fat = $fatCalories / 9;
    
            // Update entity with calculated values
            $userNutrition->setCalorie($calorie);
            $userNutrition->setProtein($protein);
            $userNutrition->setCarbs($carbs);
            $userNutrition->setFat($fat);
    
            // Flush changes to the database
            $entityManager->flush();
    
            // Redirect to index page or any other page as needed
            return $this->redirectToRoute('app_user_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the form if not submitted or invalid
        return $this->renderForm('user_nutrition/edit.html.twig', [
            'user_nutrition' => $userNutrition,
            'form' => $form,
        ]);
    }
    
    

    #[Route('/{user}', name: 'app_user_nutrition_show', methods: ['GET'])]
    public function show(UserNutrition $userNutrition): Response
    {
        return $this->render('user_nutrition/show.html.twig', [
            'user_nutrition' => $userNutrition,
        ]);
    }

    

    #[Route('/{user}', name: 'app_user_nutrition_delete', methods: ['POST'])]
    public function delete(Request $request, UserNutrition $userNutrition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userNutrition->getUser()->getIdu(), $request->request->get('_token'))) {
            $entityManager->remove($userNutrition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
