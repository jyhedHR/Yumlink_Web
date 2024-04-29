<?php

namespace App\Controller;

use App\Entity\Defis;
use App\Form\DefisType;
use App\Repository\DefisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/defis/admin')]
class DefisAdminController extends AbstractController
{
    #[Route('/', name: 'app_defis_admin_index', methods: ['GET'])]
    public function index(DefisRepository $defisRepository): Response
    {
        return $this->render('defis_admin/index.html.twig', [
            'defis' => $defisRepository->findAll(),
        ]);
    }

  
   

    #[Route('/{idD}/edit', name: 'app_defis_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Defis $defi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefisType::class, $defi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_defis_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('defis_admin/edit.html.twig', [
            'defi' => $defi,
            'form' => $form,
        ]);
    }

    #[Route('/{idD}', name: 'app_defis_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Defis $defi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defi->getIdD(), $request->request->get('_token'))) {
            $entityManager->remove($defi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_defis_admin_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/Calender_admin', name: 'app_defis_calender_admin', methods: ['GET'])]
    public function calender(DefisRepository $defisRepository): Response
    {
        $defis = $defisRepository->findAll();
    
        $events = [];
        foreach ($defis as $defi) {
            // Format the Defis data into an event
            $event = [
                'title' => $defi->getNomD(),
                
                'Date' => $defi->getDelai(),
                'start' => $defi->getDelai()->format('Y-m-d'), 
                
                'discription' => $defi->getDisD(), 
            ];
            $events[] = $event;
        }
    
        return $this->render('defis_admin/calendar.html.twig', [
            'events' => json_encode($events), // Pass events as JSON to the template
        ]);
    }
   
}
