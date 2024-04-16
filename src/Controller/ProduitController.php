<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;




#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    #[Route('/admin', name: 'app_produit_indexadmin', methods: ['GET'])]
    public function index_admin(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/indexadmin.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/search/product', name: 'search_product', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        // Retrieve the search query from the request
        $query = $request->query->get('query');

        // Perform the search logic here
        $entityManager = $this->getDoctrine()->getManager();
        $results = $entityManager->getRepository(Produit::class)->searchByNom($query);

        // Format the search results as needed
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'id' => $result->getId(),
                'nom' => $result->getNom(),
                // Add other properties as needed
            ];
        }

        // Return the search results as JSON response
        return $this->json($formattedResults);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
public function new(Request $request,EntityManagerInterface $em): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageFile')->getData();

        if ($imageFile) {
            // Generate a unique filename
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload exception
                $this->addFlash('error', 'An error occurred while uploading the image.');
                return $this->redirectToRoute('app_produit_new');
            }

            // Set the image property in the entity to the relative path of the uploaded file
            $produit->setImage('frontend/assets/images/'.$newFilename);
        }

        // Persist the entity
        $em->persist($produit);
        $em->flush();

        // Redirect to the index page or any other page as needed
        return $this->redirectToRoute('app_produit_indexadmin');
    }

    // If form is not submitted or not valid, render the form
    return $this->render('produit/new.html.twig', [
        'form' => $form->createView(),
    ]);
}




    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [    
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_produit_new');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $produit->setImage('frontend/assets/images/'.$newFilename);
            }
    



            $entityManager->flush();

            return $this->redirectToRoute('app_produit_indexadmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_indexadmin', [], Response::HTTP_SEE_OTHER);
    }
}
