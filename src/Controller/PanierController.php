<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Form\CommandeType;
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
use App\Service\StripeService;

use \Exception;



#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
public function index(PanierRepository $panierRepository, EntityManagerInterface $entityManager,Request $request): Response
{
    // Fetch paniers along with associated Produit entities using custom DQL query
    $query = $entityManager->createQuery(
        'SELECT p, produit FROM App\Entity\Panier p
        LEFT JOIN p.produit produit'
    );
    $paniers = $query->getResult();

         // Use $stripeService to create a charge
   
       

    return $this->render('panier/index.html.twig', [
        'paniers' => $paniers,
    ]);
}

#[Route('/chef', name: 'app_panier_indexchef', methods: ['GET'])]
public function indexchef(PanierRepository $panierRepository, EntityManagerInterface $entityManager,Request $request): Response
{
    // Fetch paniers along with associated Produit entities using custom DQL query
    $query = $entityManager->createQuery(
        'SELECT p, produit FROM App\Entity\Panier p
        LEFT JOIN p.produit produit'
    );
    $paniers = $query->getResult();

         // Use $stripeService to create a charge
   
       

    return $this->render('panier/indexchef.html.twig', [
        'paniers' => $paniers,
    ]);
}


#[Route('/panier/delete-all', name: 'panier_delete_all', methods: ['POST'])]
public function deleteAll(Request $request,EntityManagerInterface $entityManager): Response
{
        
    $id_client = $request->request->get('id_client');
    
        // Query to find all items in the "panier" for idClient 3
        $repository = $entityManager->getRepository(Panier::class);
        $items = $repository->findBy(['idClient' => $id_client]);

        // Remove each item from the database
        foreach ($items as $item) {
            $entityManager->remove($item);
        }

        // Flush changes to the database
        $entityManager->flush();
   

    // Redirect the user to the panier index or any other desired page
    return $this->redirectToRoute('app_panier_index');
}
#[Route('/panier/delete-all_chef', name: 'panier_delete_allchef', methods: ['POST'])]
public function deleteAllchef(Request $request,EntityManagerInterface $entityManager): Response
{
        
    $id_client = $request->request->get('id_client');
    
        // Query to find all items in the "panier" for idClient 3
        $repository = $entityManager->getRepository(Panier::class);
        $items = $repository->findBy(['idClient' => $id_client]);

        // Remove each item from the database
        foreach ($items as $item) {
            $entityManager->remove($item);
        }

        // Flush changes to the database
        $entityManager->flush();
   

    // Redirect the user to the panier index or any other desired page
    return $this->redirectToRoute('app_panier_indexchef');
}


    #[Route('/admin', name: 'app_panier_indexadmin', methods: ['GET'])]
    public function indexadmin(PanierRepository $panierRepository, EntityManagerInterface $entityManager,Request $request): Response
    {
        // Fetch paniers along with associated Produit entities using custom DQL query
        $query = $entityManager->createQuery(
            'SELECT p, produit FROM App\Entity\Panier p
            LEFT JOIN p.produit produit'
        );
        $paniers = $query->getResult();
    
            
    
            // Create a new Commande entity and set the current system date and the user ID
            $commande = new Commande();
            $commande->setDate(new \DateTime());
            $commande->setIdClient(3);
    
            // Create the form with the populated Commande entity
            $form = $this->createForm(CommandeType::class, $commande);
    
            $form->handleRequest($request);
            
                // Save the submitted data to the database
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commande);
                $entityManager->flush();
    
                // Redirect to a success page or do something else
           
    
        return $this->render('panier/indexadmin.html.twig', [
            'paniers' => $paniers,
        ]);


   
        // Get the current user ID (assuming you are using Symfony's security component)
    
  


    
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


#[Route('/newchef', name: 'app_panier_newchef', methods: ['POST'])]
public function newchef(Request $request, EntityManagerInterface $entityManager): Response
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
    return $this->redirectToRoute('app_panier_indexchef');
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

            return $this->redirectToRoute('app_panier_indexadmin', [], Response::HTTP_SEE_OTHER);
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
    #[Route('/chef/{idp}', name: 'app_panier_deletechef', methods: ['POST'])]
    public function deletechef(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_indexchef', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/admin/{idp}', name: 'app_panier_deleteadmin', methods: ['POST'])]
    public function deleteadmin(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_indexadmin', [], Response::HTTP_SEE_OTHER);
    }
}
