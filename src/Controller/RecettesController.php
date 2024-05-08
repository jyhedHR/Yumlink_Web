<?php

namespace App\Controller;

use App\Entity\FavoriteRecipes;
use App\Entity\Recettes;
use App\Entity\User;
use App\Entity\Ingredient;
use App\Entity\RecettesIngredient;
use App\Form\RecettesType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/recettes')]
class RecettesController extends AbstractController
{
    #[Route('/', name: 'app_recettes_index', methods: ['GET'])]
    public function index(RecettesRepository $recettesRepository): Response
    {
        return $this->render('recettes/index.html.twig', [
            'recettes' => $recettesRepository->findAll(),
        ]);
    }
    #[Route('/indexChef', name: 'indexChef', methods: ['GET'])]
    public function indexChef(RecettesRepository $recettesRepository): Response
    {

        return $this->render('recettes/indexChef.html.twig', [
            'recettes' => $recettesRepository->findAll(),
        ]);
    }

    #[Route('/indexAdmin', name: 'indexAdmin', methods: ['GET'])]
    public function indexAdmin(RecettesRepository $recettesRepository): Response
    {

        return $this->render('recettes/indexAdmin.html.twig', [
            'recettes' => $recettesRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_recettes_new', methods: ['GET', 'POST'])]
    public function new(SecurityController $securityC,  Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recettes();
        $form = $this->createForm(RecettesType::class, $recette);
        $form->handleRequest($request);
        $id = $securityC->getUser()->getIdU();
        $user =  $entityManager->getReference(User::class, $id);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedIngredients = $form->get('ingredients')->getData();


            foreach ($selectedIngredients as $ingredient) {
                $recetteIngredient = new RecettesIngredient();
                $recetteIngredient->setRecette($recette);
                $recetteIngredient->setIngredient($ingredient);
                $entityManager->persist($recetteIngredient);
            }
            $imageFile = $form->get('imgsrc')->getData();

            if ($imageFile) {
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                // Update the recette entity with the file path
                $recette->setImgsrc('frontend/assets/images/' . $fileName);
            }
            $recette->setUser($user);
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_mesRecettes_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recettes/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }



    #[Route('/{idR}', name: 'app_recettes_show_chef_2', methods: ['GET'])]
    public function showChef2(Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        $recetteId = $recette->getIdR();
        $recettesIngredientRepository = $entityManager->getRepository(RecettesIngredient::class);
        $recettesIngredients = $recettesIngredientRepository->findBy(['recette' => $recetteId]);

        $ingredientIds = [];
        foreach ($recettesIngredients as $recettesIngredient) {
            $ingredientIds[] = $recettesIngredient->getIngredient()->getIdIng();
        }

        // Retrieve the ingredients by their IDs
        $ingredients = $entityManager->getRepository(Ingredient::class)->findBy(['idIng' => $ingredientIds]);

        return $this->render('recettes/showChef2.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }
   
    #[Route('/{idR}', name: 'app_recettes_show_chef', methods: ['GET'])]
    public function showChef(Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        $recetteId = $recette->getIdR();
        $recettesIngredientRepository = $entityManager->getRepository(RecettesIngredient::class);
        $recettesIngredients = $recettesIngredientRepository->findBy(['recette' => $recetteId]);

        $ingredientIds = [];
        foreach ($recettesIngredients as $recettesIngredient) {
            $ingredientIds[] = $recettesIngredient->getIngredient()->getIdIng();
        }

        // Retrieve the ingredients by their IDs
        $ingredients = $entityManager->getRepository(Ingredient::class)->findBy(['idIng' => $ingredientIds]);

        return $this->render('recettes/showChef.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }
    
   
    
    #[Route('/admin/new', name: 'app_recettes_new_admin', methods: ['GET', 'POST'])]
    public function newA(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recettes();
        $form = $this->createForm(RecettesType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedIngredients = $form->get('ingredients')->getData();
            //$recette->setIngredients($selectedIngredients);

            foreach ($selectedIngredients as $ingredient) {
                $recetteIngredient = new RecettesIngredient();
                $recetteIngredient->setRecette($recette);
                $recetteIngredient->setIngredient($ingredient);
                $entityManager->persist($recetteIngredient);
            }
            $imageFile = $form->get('imgsrc')->getData();
            dump($form->getData());
            if ($imageFile) {
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                // Update the recette entity with the file path
                $recette->setImgsrc('frontend/assets/images/' . $fileName);
            }

            $entityManager->persist($recette);
            $entityManager->flush();
            return $this->redirectToRoute('indexAdmin', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('recettes/newAdmin.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }
    #[Route('/{idR}', name: 'app_recettes_show', methods: ['GET'])]
    public function show(Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        $recetteId = $recette->getIdR();
        $recettesIngredientRepository = $entityManager->getRepository(RecettesIngredient::class);
        $recettesIngredients = $recettesIngredientRepository->findBy(['recette' => $recetteId]);

        $ingredientIds = [];
        foreach ($recettesIngredients as $recettesIngredient) {
            $ingredientIds[] = $recettesIngredient->getIngredient()->getIdIng();
        }

        // Retrieve the ingredients by their IDs
        $ingredients = $entityManager->getRepository(Ingredient::class)->findBy(['idIng' => $ingredientIds]);

        return $this->render('recettes/show.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/admin/{idR}', name: 'app_recettes_show_admin', methods: ['GET'])]
    public function showA(Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        $recetteId = $recette->getIdR();
        $recettesIngredientRepository = $entityManager->getRepository(RecettesIngredient::class);
        $recettesIngredients = $recettesIngredientRepository->findBy(['recette' => $recetteId]);

        $ingredientIds = [];
        foreach ($recettesIngredients as $recettesIngredient) {
            $ingredientIds[] = $recettesIngredient->getIngredient()->getIdIng();
        }

        // Retrieve the ingredients by their IDs
        $ingredients = $entityManager->getRepository(Ingredient::class)->findBy(['idIng' => $ingredientIds]);

        return $this->render('recettes/showAdmin.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }
    #[Route('/{idR}/edit', name: 'app_recettes_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
{
    // Create the form and handle the request
    $form = $this->createForm(RecettesType::class, $recette);
    $form->handleRequest($request);

    // If the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $photoD = $form->get('imgsrc')->getData();
        if ($photoD) {
            // Generate a unique filename
            $originalFilename = pathinfo($photoD->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = preg_replace('/[^\w-]/', '_', $originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoD->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $photoD->move($this->getParameter('images_directory'), $newFilename);
                // Update the recette's image source
                $recette->setImgsrc($newFilename);
                // Consider deleting the old image if necessary
            } catch (FileException $e) {
                // Handle file upload exception
                $this->addFlash('error', 'An error occurred while uploading the image.');
                return $this->redirectToRoute('app_recettes_edit', ['idR' => $recette->getIdR()]);
            }
        }

        // Handle ingredients
        $selectedIngredients = $form->get('ingredients')->getData();
        // Clear existing ingredients
        $recette->getIngredients()->clear();
        
        // Add new ingredients
        foreach ($selectedIngredients as $ingredient) {
            $recetteIngredient = new RecettesIngredient();
            $recetteIngredient->setRecette($recette);
            $recetteIngredient->setIngredient($ingredient);
            $entityManager->persist($recetteIngredient);
        }

        // Save changes
        $entityManager->flush();

        // Add a success flash message
        $this->addFlash('success', 'Recipe updated successfully.');

        // Redirect to the appropriate route
        return $this->redirectToRoute('indexChef', [], Response::HTTP_SEE_OTHER);
    }

    // Render the form
    return $this->renderForm('recettes/edit.html.twig', [
        'recette' => $recette,
        'form' => $form,
    ]);
}


    #[Route('/{idR}', name: 'app_recettes_delete', methods: ['POST'])]
    public function delete(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('indexChef', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/{idR}', name: 'app_recettes_delete_admin', methods: ['POST'])]
    public function deleteA(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('indexAdmin', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/search', name: 'search_recipes', methods: ['GET'])]
    public function searchRecipes(Request $request)
    {
        $query = $request->query->get('query');

        $recipes = $this->getDoctrine()
            ->getRepository(Recettes::class)
            ->searchByNameOrChef($query);
        return $this->render('recettes/search_results.html.twig', [
            'searchResults' =>  $recipes,
        ]);
    }
    #[Route('/{idR}/favorite', name: 'favorite_recipe', methods: ['POST'])]
    public function addToFavorites(SecurityController $securityC, Recettes $recette, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('fav' . $recette->getIdr(), $request->request->get('_token'))) {
            $id = $securityC->getUser()->getIdU();
            $user = $entityManager->getReference(User::class, $id);
            $favoriteRecipe = new FavoriteRecipes();
            $favoriteRecipe->setUser($user);
            $favoriteRecipe->setRecipe($recette);
            $entityManager->persist($favoriteRecipe);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_recettes_index');
    }

    #[Route('/show_favorite', name: 'app_favorite_show', methods: ['GET'])]
    public function favoriteRecipes(SecurityController $securityC, EntityManagerInterface $entityManager): Response
    {

        $id = $securityC->getUser()->getIdU();
        $user = $entityManager->getReference(User::class, $id);
        $favoriteRecipes = $entityManager->getRepository(FavoriteRecipes::class)->findBy(['user' => $user]);
        dump($favoriteRecipes);
        $recipeIds = [];
        foreach ($favoriteRecipes as $favoriteRecipe) {
            $recipeIds[] = $favoriteRecipe->getRecipe()->getIdR();
        }


        $favoriteRecipesDetails = $entityManager->getRepository(Recettes::class)->findBy(['idR' => $recipeIds]);

        return $this->render('recettes/mes_fav.html.twig', [
            'favoriteRecipes' => $favoriteRecipesDetails,
        ]);
    }

    #[Route('/mes_recettes', name: 'app_mesRecettes_show', methods: ['GET'])]
    public function MesRecettesChef(SecurityController $securityC, EntityManagerInterface $entityManager): Response
    {
        $id = $securityC->getUser()->getIdU();
        $user = $entityManager->getReference(User::class, $id);
        $Recipes = $entityManager->getRepository(Recettes::class)->findBy(['user' => $user]);



        return $this->render('recettes/mesRecettesChef.html.twig', [
            'Recipes' => $Recipes,
        ]);
    }

    #[Route('/{idR}/remove_favorite', name: 'remove_favorite_recipe', methods: ['POST'])]
    public function removeFromFavorites(SecurityController $securityC, Recettes $recette, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('remove' . $recette->getIdr(), $request->request->get('_token'))) {
            $id = $securityC->getUser()->getIdU();
            $user = $entityManager->getReference(User::class, $id);
            $favoriteRecipe = $entityManager->getRepository(FavoriteRecipes::class)->findOneBy([
                'user' => $user,
                'recipe' => $recette,
            ]);

            if ($favoriteRecipe) {
                $entityManager->remove($favoriteRecipe);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_recettes_index');
    }
    #[Route('/recettes/charts', name: 'admin_recipes_and_activity_charts', methods: ['GET'])]
    public function displayCharts(EntityManagerInterface $entityManager, RecettesRepository $recettesRepository): Response
    {
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
        return $this->render('recettes/chart.html.twig', [
            'labelsPerCategory' => json_encode($labelsPerCategory),
            'dataPerCategory' => json_encode($dataPerCategory),
            'monthsActivity' => json_encode($monthsActivity),
            'recipeCountsActivity' => json_encode($recipeCountsActivity),
            'labelsPopularChefs' => json_encode($labelsPopularChefs),
            'dataPopularChefs' => json_encode($dataPopularChefs),
        ]);
    }



    #[Route('/filter', name: 'filtrer_recettes', methods: ['GET'])]
    public function filterRecipes(Request $request): Response
    {
        // Retrieve filtering criteria from query parameters
        $calories = $request->query->get('calorie');
        $protein = $request->query->get('protein');

        // Fetch filtered recipes based on the provided criteria
        $filteredRecipes = $this->getDoctrine()->getRepository(Recettes::class)
            ->findByCaloriesAndProtein($calories, $protein);

        // Render the filtered recipes template with the fetched recipes
        return $this->render('recettes/filtered_recipes.html.twig', [
            'recipes' => $filteredRecipes,
        ]);
    }
}
