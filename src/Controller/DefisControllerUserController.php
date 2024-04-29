<?php

namespace App\Controller;

use App\Entity\Defis;
use App\Entity\User;
use App\Entity\Participant;
use App\Form\Defis1Type;
use App\Form\Participant1Type;
use App\Repository\DefisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/defis/controller/user')]
class DefisControllerUserController extends AbstractController
{
    #[Route('/', name: 'app_defis_controller_user_index', methods: ['GET'])]
    public function index(Request $request, DefisRepository $defisRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $defis = [];
        if ($searchTerm) {
            $defis = $defisRepository->findByNom($searchTerm);
        } else {
            $defis = $defisRepository->findActiveDefis();
        }
    
    
        return $this->render('defis_controller_user/index.html.twig', [
            'defis' => $defis,
        ]);
    }

    #[Route('/defis/participer/{id}', name: 'app_participer_defis', methods: ['GET', 'POST'])]
    public function participer(Defis $defis, Request $request, EntityManagerInterface $entityManager): Response
    {
        $participant = new Participant(); // Create a new Participant entity
        $form = $this->createForm(Participant1Type::class, $participant); // Create the form
    
        $form->handleRequest($request);
        // Handle form submission
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            // Handle image saving logic
            $photoP = $form->get('photoP')->getData();
            
            if ($photoP) {
                // Generate a unique filename
                $originalFilename = pathinfo($photoP->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoP->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $photoP->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_participer_defis');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $participant->setPhotoP('assets/images/'.$newFilename);
            }
    
            // Set the relationship between Participant and Defis
            $participant->setDefis($defis);
            $id_user = $form->get('user')->getData();
            $user = $entityManager->getReference(User::class, $id_user);
            $participant->setUser($user);

    
            // Save the Participant entity
            $entityManager->persist($participant);
            $entityManager->flush();
    
            // Redirect back to the Defis index
            return $this->redirectToRoute('app_participant_controller_user_index');
        }
    
        return $this->render('defis_controller_user/participer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/historique_user', name: 'app_defis_historique_user', methods: ['GET'])]
public function historique(DefisRepository $defisRepository): Response
{
    $expiredDefis = $defisRepository->findExpiredDefis();
    
    return $this->render('defis_controller_user/historique_user.html.twig', [
        'defis' => $expiredDefis,
    ]);
}
   

   
}