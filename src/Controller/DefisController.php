<?php

namespace App\Controller;
use App\Entity\User; 
use App\Entity\Defis;
use App\Form\DefisType;
use App\Repository\DefisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\FormError;
use App\Service\SmsSender;


#[Route('/defis')]
class DefisController extends AbstractController
{
   
    #[Route('/', name: 'app_defis_index', methods: ['GET'])]
    public function index(Request $request, DefisRepository $defisRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $defis = [];
    
        if ($searchTerm) {
            $defis = $defisRepository->findByNom($searchTerm);
        } else {
            // Retrieve defis sorted by delai (deadline) date and heure (time)
            $defis = $defisRepository->findActiveDefis();
        }
    
        return $this->render('defis/index.html.twig', [
            'defis' => $defis,
        ]);
    }
    
    #[Route('/new', name: 'app_defis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  SmsSender $smsSender,): Response
    {
        $defi = new Defis();
        $form = $this->createForm(DefisType::class, $defi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $request->request->get('id_client');
    
            // Check if the challenge date is expired
            $now = new \DateTime();
            $defiDate = $defi->getDelai();
            $defiTime = $defi->getHeure();
    
            if ($defiDate < $now || ($defiDate == $now && $defiTime < $now->format('H:i'))) {
                $form->get('delai')->addError(new FormError('La date du défi sont déjà passées.'));
                $form->get('heure')->addError(new FormError('Lheure du défi sont déjà passées.'));
                return $this->renderForm('defis/new.html.twig', [
                    'defi' => $defi,
                    'form' => $form,
                ]);
            }
    
            $photoD = $form->get('photoD')->getData();
            if ($photoD) {
                // Generate a unique filename
                $originalFilename = pathinfo($photoD->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoD->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $photoD->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_defis_new');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $defi->setPhotoD('assets/images/'.$newFilename);
            }
    
            // Set the user to the Defis entity
            $id_user = $form->get('user')->getData();
            $user = $entityManager->getReference(User::class, $id_user);
            $defi->setUser($user);
    
            // Persist and flush the entity
            $entityManager->persist($defi);
            $entityManager->flush();
    
            $smsSender->sendSms('+21626956338', 'Un nouveau défi a été ajouter');
            $this->addFlash('sms_sent', true);
    
            return $this->redirectToRoute('app_defis_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('defis/new.html.twig', [
            'defi' => $defi,
            'form' => $form,
        ]);
    }

   

    #[Route('/{idD}/edit', name: 'app_defis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Defis $defi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefisType::class, $defi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the challenge date is expired
            $now = new \DateTime();
            $defiDate = $defi->getDelai();
            $defiTime = $defi->getHeure();
    
            if ($defiDate < $now || ($defiDate == $now && $defiTime < $now->format('H:i'))) {
                $form->get('delai')->addError(new FormError('La date du défi est déjà passée.'));
                $form->get('heure')->addError(new FormError('Lheure du défi est déjà passée.'));
                return $this->renderForm('defis/edit.html.twig', [
                    'defi' => $defi,
                    'form' => $form,
                ]);
            }
    
            $photoD = $form->get('photoD')->getData();
    
            // Check if a new image was uploaded
            if ($photoD) {
                // Generate a unique filename
                $originalFilename = pathinfo($photoD->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$photoD->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $photoD->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_defis_edit', ['idD' => $defi->getIdD()]);
                }
    
                // Set the image path in the entity to the relative path of the uploaded file
                $defi->setPhotoD('assets/images/'.$newFilename);
            }
    
            // Flush changes to the entity
            $entityManager->flush();
    
            return $this->redirectToRoute('app_defis_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('defis/edit.html.twig', [
            'defi' => $defi,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{idD}', name: 'app_defis_delete', methods: ['POST'])]
    public function delete(Request $request, Defis $defi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defi->getIdD(), $request->request->get('_token'))) {
            $entityManager->remove($defi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_defis_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/historique', name: 'app_defis_historique', methods: ['GET'])]
public function historique(DefisRepository $defisRepository): Response
{
    $expiredDefis = $defisRepository->findExpiredDefis();
    
    return $this->render('defis/historique.html.twig', [
        'defis' => $expiredDefis,
    ]);
}
    
}
