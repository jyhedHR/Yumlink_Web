<?php

namespace App\Controller;

use App\Entity\Recettes;
use App\Entity\User;
use App\Entity\UserNutrition;
use App\Form\UserNutritionType;
use App\Repository\UserNutritionRepository;
use App\Repository\RecettesRepository; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


//pdf 
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/nutrition')]
class UserNutritionController extends AbstractController
{
    

    #[Route('/', name: 'app_user_nutrition_index', methods: ['GET'])]
    public function index(UserNutritionRepository $userNutritionRepository, TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();

           // Check if the user is authenticated and is an instance of UserInterface
    if ($user instanceof UserInterface) {
        // Retrieve the user's ID
        $userId = $user->getIdu();

        // Fetch user nutrition records based on the user's ID
        $userNutritions = $userNutritionRepository->findBy(['user' => $userId]);
    }
            return $this->render('user_nutrition/index.html.twig', [
                'user_nutritions' =>  $userNutritions,
            ]);
        }
    
    


    #[Route('/new', name: 'app_user_nutrition_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
     // Get the currently authenticated user
     $user = $security->getUser();

     // Check if the user is authenticated
     if ($user) {
         $userNutrition = new UserNutrition();
         $userNutrition->setUser($user);
 
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
        $protein = $proteinCalories / 4 - 100;
        $carbs = $carbCalories / 4 -100;
        $fat = $fatCalories / 9 - 30 ;

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
            $calorie = $bmr * $activityFactor - 400;
    
            // Distribute macronutrients
            $proteinRatio = 0.3; // 30%
            $carbRatio = 0.4; // 40%
            $fatRatio = 0.3; // 30%
            $proteinCalories = $calorie * $proteinRatio;
            $carbCalories = $calorie * $carbRatio;
            $fatCalories = $calorie * $fatRatio;
            $protein = $proteinCalories / 4 - 100;
            $carbs = $carbCalories / 4 - 100;
            $fat = $fatCalories / 9 - 30;
    
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
    public function delete(Request $request, UserNutrition $userNutrition, RecettesRepository $recettesRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userNutrition->getUser()->getIdu(), $request->request->get('_token'))) {
            $entityManager->remove($userNutrition);
            $entityManager->flush();

            // Delete recommendations associated with this user
       //$recetteRepository->deleteRecommendationsForUser($userNutrition->getUser()->getIdu());
  
        }

        return $this->redirectToRoute('app_user_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/generate-pdf/{userId}', name: 'app_generate_pdf')]
        public function generatePdf(UserNutritionRepository $userNutritionRepository, $userId): Response
        {
                    // Retrieve the user with the given ID
                    $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

                    // Retrieve the user's nutrition data from the repository
                    $userNutrition = $userNutritionRepository->findOneBy(['user' => $user]);

                    // Make sure userNutrition exists before generating PDF
                    if (!$userNutrition) {
                        throw $this->createNotFoundException('User nutrition data not found.');
                    }

                    // Configure Dompdf options
                    $options = new Options();
                    $options->set('isHtml5ParserEnabled', true);

                    // Instantiate Dompdf with options
                    $dompdf = new Dompdf($options);

                    // Render the Twig template to HTML
                    $html = $this->renderView('user_nutrition/body_bilan.html.twig', [
                        'userNutrition' => $userNutrition,
                    ]);

                    // Load HTML content into Dompdf
                    $dompdf->loadHtml($html);

                    // Set paper size and orientation
                    $dompdf->setPaper('A4', 'portrait');

                    // Render PDF (optional: you can directly output the PDF, or save it to a file)
                    $dompdf->render();

                    // Output PDF to the browser
                    return new Response(
                        $dompdf->output(),
                        Response::HTTP_OK,
                        [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="personalized_nutritional_body_bilan.pdf"',
                        ]
                    );
        }

    #[Route('/{id}/history', name: 'user_nutrition_history')]
    public function history(int $id, UserNutritionRepository $repository): Response
    {
        $userNutrition = $repository->find($id);
        $history = $repository->getAuditEntries($userNutrition);

        return $this->render('user_nutrition/history.html.twig', [
            'userNutrition' => $userNutrition,
            'history' => $history,
        ]);
    }


}