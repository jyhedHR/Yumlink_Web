<?php

namespace App\Controller;

use App\Entity\FavoriteRecipes;
use App\Entity\Recettes;
use App\Entity\User;
use App\Entity\Ingredient ;
use App\Entity\RecettesIngredient ;
use App\Form\RecettesType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    #[Route('/indexChef', name: 'indexChef' , methods: ['GET'])]
    public function indexChef(RecettesRepository $recettesRepository): Response
    {
       
        return $this->render('recettes/indexChef.html.twig', [
            'recettes' => $recettesRepository->findAll(),
        ]);
    }

    #[Route('/indexAdmin', name: 'indexAdmin' , methods: ['GET'])]
    public function indexAdmin(RecettesRepository $recettesRepository): Response
    {
        
        return $this->render('recettes/indexAdmin.html.twig', [
            'recettes' => $recettesRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_recettes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recettes();
        $form = $this->createForm(RecettesType::class, $recette);
        $form->handleRequest($request);
       
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
                    $this->getParameter('uploads_directory'), 
                    $fileName
                );
    
                // Update the recette entity with the file path
                $recette->setImgsrc('/uploads/'.$fileName);
            }

            $entityManager->persist($recette);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_recettes_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('recettes/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
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
   dump($form->getData()) ; 
            if ($imageFile) {
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'), 
                    $fileName
                );
    
                // Update the recette entity with the file path
                $recette->setImgsrc('/uploads/'.$fileName);
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
    $form = $this->createForm(RecettesType::class, $recette);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imgsrc')->getData();
        
        // Check if a new image file is uploaded
        if ($imageFile) {
            // Generate a unique filename
            $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
            // Move the uploaded file to the desired directory
            $imageFile->move(
                $this->getParameter('uploads_directory'), 
                $fileName
            );
            // Set the image source attribute of the recipe entity
            $recette->setImgsrc($fileName);
        }
        $selectedIngredients = $form->get('ingredients')->getData();
        foreach ($selectedIngredients as $ingredient) {
            $recetteIngredient = new RecettesIngredient();
            $recetteIngredient->setRecette($recette); 
            $recetteIngredient->setIngredient($ingredient);
            $entityManager->persist($recetteIngredient); 
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_recettes_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('recettes/edit.html.twig', [
        'recette' => $recette,
        'form' => $form,
    ]);
}
  
    #[Route('/{idR}', name: 'app_recettes_delete', methods: ['POST'])]
    public function delete(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recettes_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/{idR}', name: 'app_recettes_delete_admin', methods: ['POST'])]
    public function deleteA(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getIdR(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('indexAdmin', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/search_recipes', name: 'search_recipes', methods: ['GET'])]
    public function searchRecipes(Request $request)
{
    $query = $request->query->get('query');

    $recipes = $this->getDoctrine()
        ->getRepository(Recettes::class)
        ->searchByNameOrChef($query); 

    return $this->render('recettes/_search_results.html.twig', [
        'recipes' => $recipes,
        'query' => $query,
    ]);
}
#[Route('/{idR}/favorite', name: 'favorite_recipe', methods: ['POST'])]
public function addToFavorites(Recettes $recette, EntityManagerInterface $entityManager, Request $request): Response
{
    if ($this->isCsrfTokenValid('fav' . $recette->getIdr(), $request->request->get('_token'))) {
    $user = $entityManager->getRepository(User::class)->find(29);
    $favoriteRecipe = new FavoriteRecipes(); 
    $favoriteRecipe->setUser($user);
    $favoriteRecipe->setRecipe($recette);
    $entityManager->persist($favoriteRecipe);
    $entityManager->flush();
    }
    return $this->redirectToRoute('app_recettes_index');
}

#[Route('/show_favorite', name: 'app_favorite_show' , methods: ['GET'])]
public function favoriteRecipes(EntityManagerInterface $entityManager): Response
{

     $user = $entityManager->getRepository(User::class)->find(29);
     $favoriteRecipes = $entityManager->getRepository(FavoriteRecipes::class)->findBy(['user' => $user]);
  dump($favoriteRecipes) ; 
     $recipeIds = [];
     foreach ($favoriteRecipes as $favoriteRecipe) {
         $recipeIds[] = $favoriteRecipe->getRecipe()->getIdR();
     }
 
    
     $favoriteRecipesDetails = $entityManager->getRepository(Recettes::class)->findBy(['idR' => $recipeIds]);
 
     return $this->render('recettes/mes_fav.html.twig', [
         'favoriteRecipes' => $favoriteRecipesDetails,
     ]);
}

#[Route('/{idR}/remove_favorite', name: 'remove_favorite_recipe', methods: ['POST'])]
public function removeFromFavorites(Recettes $recette, EntityManagerInterface $entityManager, Request $request): Response
{
    if ($this->isCsrfTokenValid('remove' . $recette->getIdr(), $request->request->get('_token'))) {
        $user = $entityManager->getRepository(User::class)->find(29);
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
   
}
