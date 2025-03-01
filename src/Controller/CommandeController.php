<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\AdresseRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Stripe\Exception\ApiErrorException;
use Knp\Snappy\Pdf;



#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

   
    #[Route('/new1', name: 'app_commande_new1', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager, NotifierInterface $notifier): Response
    {
        // Create a new instance of Commande
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setIdClient(15); // Set client ID as needed
    
        // Persist the Commande entity to the database
        $entityManager->persist($commande);
        $entityManager->flush();
      
        // Create an instance of KnpSnappyBundle Pdf
      
   
    
      
        return $this->render('panier/index.html.twig', [
          
        ]);
    }
    
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new( SecurityController $session , AdresseRepository $adresseRepository ): Response
    {
        $user = $session->getUser();
        dump($user);
      
        return $this->render('commande/new.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'adresses' => $adresseRepository->findAll(),     
           ]);
    }



    


    

    #[Route('/{idCom}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{idCom}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);

    }

    #[Route('/{idCom}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCom(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
