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
}
