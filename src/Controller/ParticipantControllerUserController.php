<?php

namespace App\Controller;

use App\Entity\Defis;
use App\Entity\Vote;
use App\Entity\Participant;
use App\Form\Participant1Type;
use App\Repository\DefisRepository;
use App\Repository\ParticipantRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant/controller/user')]
class ParticipantControllerUserController extends AbstractController
{
    #[Route('/', name: 'app_participant_controller_user_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant_controller_user/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    #[Route('/defis_participant/{defis_id}', name: 'app_participant_par_defis', methods: ['GET'])]
    public function listeParDefis(int $defis_id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager): Response
    {
        $defis = $entityManager->getReference(Defis::class, $defis_id);

        $participants = $participantRepository->findByDefis($defis_id);
        return $this->render('participant_controller_user/defis_participants.html.twig', [
            'participants' => $participants,
            'defisname' => $defis->getNomD(),
            'defiId'=> $defis->getIdD(),
        ]);
    }

    #[Route('/{idpart}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Participant1Type::class, $participant);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist changes to the entity
            $entityManager->persist($participant);
            $entityManager->flush();
    
            // Redirect back to the participant index page
            return $this->redirectToRoute('app_participant_controller_user_index');
        }
    
        // Render the edit form template
        return $this->renderForm('participant_controller_user/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }
   
    #[Route('/{idpart}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participant $participant, EntityManagerInterface $entityManager, SecurityController $session): Response
    {
        $user = $session->getUser();
        $userId = $user->getIdU();
    
        // Check if the user attempting to delete is the creator of the participant
        if ($participant->getUser()->getIdU() === $userId) {
            $this->addFlash('error','Vous ne pouvez pas supprimer votre propre participation');
            return $this->redirectToRoute('app_participant_controller_user_index');
        }elseif ($this->isCsrfTokenValid('delete'.$participant->getIdpart(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
            $this->addFlash('success', 'La participation a été supprimée avec succès.');
        }
    
        return $this->redirectToRoute('app_participant_controller_user_index');
    }

    #[Route('/vote/{idpart}', name: 'app_participant_vote', methods: ['POST'])] 
    public function vote(SecurityController $session, Request $request, Participant $participant, EntityManagerInterface $entityManager, VoteRepository $voteRepository): Response
    {
        $user = $session->getUser();
        $userId = $user->getIdU(); 
        if ($participant->getUser()->getIdU() === $userId) {
            $this->addFlash('error','Vous ne pouvez pas voter pour vous-même');
        }elseif ($voteRepository->hasUserVotedForParticipant($userId, $participant->getIdpart())) {
            $this->addFlash('error','Vous avez déjà voté pour cette participation.');
        } else {
            $voteValue = $request->request->get('vote'); 
            $vote = new Vote();
            $vote->setParticipant($participant);
            $vote->setUser($user);
            $participant->setVote(++$voteValue); 
            $entityManager->persist($vote);
            $entityManager->persist($participant);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_participant_controller_user_index');
    }
    

  

   
}