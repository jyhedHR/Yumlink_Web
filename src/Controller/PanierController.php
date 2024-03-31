<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Entity\Produit;



#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
public function index(PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
{
    // Fetch paniers along with associated Produit entities using custom DQL query
    $query = $entityManager->createQuery(
        'SELECT p, produit FROM App\Entity\Panier p
        LEFT JOIN p.produit produit'
    );
    $paniers = $query->getResult();

    return $this->render('panier/index.html.twig', [
        'paniers' => $paniers,
    ]);
}



#[Route('/new', name: 'app_panier_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    // Extract data from the form submission
    $id_client = $request->request->get('id_client');
    $id_produit = $request->request->get('id_produit');
    $QUANT = $request->request->get('quantite');

    // Fetch the Produit entity by id_produit
    $produit = $entityManager->getRepository(Produit::class)->find($id_produit);

    if (!$produit) {
        // Handle error if produit is not found
        throw $this->createNotFoundException('Product not found');
    }

    // Create a new Panier entity
    $panier = new Panier();
    $panier->setIdClient($id_client);
    $panier->setProduit($produit); // Associate the produit with panier
    $panier->setQuantite($QUANT);

    // Set the price and quantity from the produit entity
    $panier->setPrixtotal($produit->getPrix());
    
    

    // Persist the new Panier entity to the database
    $entityManager->persist($panier);
    $entityManager->flush();

    // Redirect to the Panier index page or any other page after adding the Panier
    return $this->redirectToRoute('app_panier_index');
}


    #[Route('/{idp}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{idp}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{idp}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
