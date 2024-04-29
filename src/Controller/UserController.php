<?php

namespace App\Controller;
use App\Entity\Adresse;
use App\Entity\User;
use App\Form\AdminType;
use App\Form\UserType;
use App\Form\AdresseType;
use App\Repository\UserRepository;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
   
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
   
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AdresseRepository $adresseRepository, SessionInterface $session): Response
    {
        $adresseId = $request->query->get('adresse_id');
        $adresse = null;
        if ($adresseId) {
            $adresse = $adresseRepository->find($adresseId);
        }
    
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
    
        $formData = $request->getSession()->get('user_form_data', []);
        if (!empty($formData)) {
            $userForm->submit($formData);
        }
    
        $userForm->handleRequest($request);
    
        if ($adresse) {
            $user->setAdresse($adresse);
        }
    
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user=$userForm->getData();
            $password=$user->getMdp();
            $hashedpassword= $this->hasher->hashPassword(
                $user,
                $password
            );
            $user->setMdp($hashedpassword);
      /** @var UploadedFile $imageFile */
      $imageFile = $userForm->get('image')->getData();

      if ($imageFile) {
          $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
          $imageFile->move(
              $this->getParameter('images_directory'), 
              $fileName
          );

          // Update the recette entity with the file path
          $user->setImage('/usersProfile/'.$fileName);
      }
     
      
            $entityManager->persist($user);
            $entityManager->flush();
            $request->getSession()->remove('user_form_data');
            return $this->redirectToRoute('app_login');
        }
  
        if ($request->isMethod('POST')) {
            $request->getSession()->set('user_form_data', $request->request->get('user_form', []));
        }

        return $this->render('user/register.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }
    

    #[Route('/{idu}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Welcome', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'userForm' => $userForm,
        ]);
    }

    #[Route('/{idu}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdu(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/nosChefs', name: 'app_chef', methods: ['GET'])]
    public function afficherChefs(UserRepository $userRepository): Response
    {
        $users = $userRepository->findByRole('chef');

        return $this->render('user/show.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/search', name: 'user_search')]
    public function search(Request $request,UserRepository $userRepository): Response
    {
        $criteria = $request->query->get('q');

        // Utiliser Doctrine pour effectuer la recherche en fonction des critères
        $users = $userRepository->findBySearchCriteria($criteria);

        // Convertir les résultats en un tableau utilisable pour la réponse JSON
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->getIdU(),
                'name' => $user->getNom(), // Modifier selon vos besoins
                // Ajoutez d'autres attributs d'utilisateur si nécessaire
            ];
        }

        // Renvoyer les résultats de la recherche au format JSON
        return new JsonResponse($usersArray);
    }
    #[Route('/{idu}/block', name: 'app_user_block', methods: ['POST'])]
    public function adminblockuser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $data = $userRepository->find($id);
        $data->setBlocked(!$data->isBlocked());
        $entityManager->persist($data);
        $entityManager->flush();
        return $this->redirectToRoute('app_user_index');
    }
    
}