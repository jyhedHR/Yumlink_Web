<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\User;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET', 'POST'])]
    public function index(ReclamationRepository $reclamationRepository , EntityManagerInterface $entityManager  , Request $request , MailerInterface $mailer,SecurityController $session ): Response
    {
        $comm = $request->request->get('reclamation');
        $id_client = $request->request->get('id_client');
        // Validate the retrieved value
        if ($comm === null || $comm === '') {
            // Handle the case where 'reclamation' is missing or empty
            // You can set a default value, return an error response, or handle it as appropriate for your application
            return new Response('Comentair field is required', Response::HTTP_BAD_REQUEST);
        }else{
            $user = $session->getUser();
            $mail=$user->getUserIdentifier();
            $reclamation = new Reclamation();
            $reclamation->setComentair($comm);
            $reclamation->setIduser($id_client); // Set client ID as needed
            $reclamation->setNomuser($mail);
            // Persist the Commande entity to the database
            $entityManager->persist($reclamation);
            $entityManager->flush();


            $email = (new Email())
            ->from('yumlink12@gmail.com') 
            ->to('jihedhorchani1234@gmail.com') 
            //->cc('exemple@mail.com') 
            //->bcc('exemple@mail.com')
            //->replyTo('test42@gmail.com')
                ->priority(Email::PRIORITY_HIGH) 
                ->subject('Reclamation')
            // If you want use text mail only
                ->text(' Reclamation a envoyer avec succes ') 
            ;
    
            // Try to send the email
                $mailer->send($email);
            // Set a flash message to indicate success
            $this->addFlash('success', 'Your reclamation has been submitted successfully.');

           

        }
        
        return $this->redirectToRoute('app_reprandre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/chef', name: 'app_reclamation_indexchef', methods: ['GET', 'POST'])]
    public function indexchef(ReclamationRepository $reclamationRepository , EntityManagerInterface $entityManager  , Request $request , MailerInterface $mailer,SecurityController $session ): Response
    {
        $comm = $request->request->get('reclamation');
        $id_client = $request->request->get('id_client');
        // Validate the retrieved value
        if ($comm === null || $comm === '') {
            // Handle the case where 'reclamation' is missing or empty
            // You can set a default value, return an error response, or handle it as appropriate for your application
            return new Response('Comentair field is required', Response::HTTP_BAD_REQUEST);
        }else{
            $user = $session->getUser();
            $mail=$user->getUserIdentifier();
            $reclamation = new Reclamation();
            $reclamation->setComentair($comm);
            $reclamation->setIduser($id_client); // Set client ID as needed
            $reclamation->setNomuser($mail);
            // Persist the Commande entity to the database
            $entityManager->persist($reclamation);
            $entityManager->flush();


            $email = (new Email())
            ->from('yumlink12@gmail.com') 
            ->to('jihedhorchani1234@gmail.com') 
            //->cc('exemple@mail.com') 
            //->bcc('exemple@mail.com')
            //->replyTo('test42@gmail.com')
                ->priority(Email::PRIORITY_HIGH) 
                ->subject('Reclamation')
            // If you want use text mail only
                ->text(' Reclamation a envoyer avec succes ') 
            ;
    
            // Try to send the email
                $mailer->send($email);
            // Set a flash message to indicate success
            $this->addFlash('success', 'Your reclamation has been submitted successfully.');

           

        }
        
        return $this->redirectToRoute('app_reprandre_indexchef', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/t', name: 'app_reclamation_indexadmin', methods: ['GET'])]
    public function indexadmin(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idr}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{idr}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idr}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdr(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_indexadmin', [], Response::HTTP_SEE_OTHER);
    }


   
}
