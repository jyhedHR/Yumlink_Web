<?php

namespace App\Controller;

use App\Entity\Recettes;

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

    #[Route('/new', name: 'app_recettes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

    #[Route('/{idR}/edit', name: 'app_recettes_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Recettes $recette, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(RecettesType::class, $recette);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imgsrc')->getData();
        if ($imageFile) {
            $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('uploads_directory'), 
                $fileName
            );
            $recette->setImgsrc($fileName);}
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
}
