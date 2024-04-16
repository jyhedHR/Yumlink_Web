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

#[Route('/defis')]
class DefisController extends AbstractController
{
    #[Route('/', name: 'app_defis_index', methods: ['GET'])]
    public function index(DefisRepository $defisRepository): Response
    {
        return $this->render('defis/index.html.twig', [
            'defis' => $defisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_defis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $defi = new Defis();
        $form = $this->createForm(DefisType::class, $defi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
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
            $userId = 39;
            $user = $entityManager->getReference(User::class, $userId); // Debugging statement to check if the user is fetched correctly
            
            // Set the user to the Defis entity
            $defi->setUser($user);
            // Persist and flush the entity
            $entityManager->persist($defi);
            $entityManager->flush();
    
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
    
        return $this->renderForm('defis/edit.html.twig', [
            'defi' => $defi,
            'form' => $form,
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
}
