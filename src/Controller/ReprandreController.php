<?php

namespace App\Controller;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Entity\Reprandre;
use App\Form\ReprandreType;
use App\Repository\ReprandreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reprandre')]
class ReprandreController extends AbstractController
{
    #[Route('/', name: 'app_reprandre_index', methods: ['GET'])]
    public function index(ReprandreRepository $reprandreRepository,ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reprandre/index.html.twig', [
            'reprandres' => $reprandreRepository->findAll(),
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/chef', name: 'app_reprandre_indexchef', methods: ['GET'])]
    public function indexchef(ReprandreRepository $reprandreRepository,ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reprandre/indexchef.html.twig', [
            'reprandres' => $reprandreRepository->findAll(),
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/reclamation/respond', name: 'app_reprandre_respond', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ReclamationRepository $reclamationRepository): Response
    {
        $id_reclamation = $request->request->get('_token');
        $id_client = $request->request->get('id_client');
       
        

       
            $reprandre = new Reprandre();
            $response = $request->request->get('response');
            $reprandre->setDescription($response);
            $reprandre->setIdClient($id_reclamation);
            $entityManager->persist($reprandre);
            $entityManager->flush();
            
            
            // Perform your response logic here
            
            // Save the response or make changes as necessary
            
            // Add a flash message or redirect after successful response
            $this->addFlash('success', 'Response submitted successfully.');





            return $this->redirectToRoute('app_reclamation_indexadmin', [], Response::HTTP_SEE_OTHER);
      
       
            
        

      
    }

    #[Route('/{id}', name: 'app_reprandre_show', methods: ['GET'])]
    public function show(Reprandre $reprandre): Response
    {
        return $this->render('reprandre/show.html.twig', [
            'reprandre' => $reprandre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reprandre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reprandre $reprandre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReprandreType::class, $reprandre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reprandre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reprandre/edit.html.twig', [
            'reprandre' => $reprandre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reprandre_delete', methods: ['POST'])]
    public function delete(Request $request, Reprandre $reprandre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reprandre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reprandre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reprandre_index', [], Response::HTTP_SEE_OTHER);
    }
}
