<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\Participant1Type;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant/admin')]
class ParticipantAdminController extends AbstractController
{
    #[Route('/', name: 'app_participant_admin_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        
        return $this->render('participant_admin/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

   
   

    #[Route('/{idpart}/edit', name: 'app_participant_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Participant1Type::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant_admin/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{idpart}', name: 'app_participant_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getIdpart(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participant_admin_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/Stat_admin', name: 'app_participant_Stat_admin', methods: ['GET'])]
    public function statistiques(ParticipantRepository $participantRepo): Response
    {
        // Retrieve all participants
        $participants = $participantRepo->findAll();
    
        // Initialize arrays to store participant data
        $participantIds = [];
        $participantVotes = [];
    
        // Extract participant data
        foreach ($participants as $participant) {
            $participantIds[] = $participant->getIdpart();
            $participantVotes[] = $participant->getVote();
        }
    
        // Prepare the data for rendering
        $participantData = [
            'participantIds' => json_encode($participantIds),
            'participantVotes' => json_encode($participantVotes),
        ];
    
        // Render the template with participant statistics data
        return $this->render('participant_admin/stats.html.twig', $participantData);
    }
    
}
