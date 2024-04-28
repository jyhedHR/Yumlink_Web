<?php

namespace App\Controller;

use Twilio\Rest\Client;
use App\Entity\Admin;
use App\Entity\User;

use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client as GuzzleHttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AdminController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
   
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $admin = new User();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez les données du formulaire
            $admin = $form->getData();
    
           
    
            // Hachez le mot de passe
            $password = $admin->getMdp();
            $hashedPassword = $this->hasher->hashPassword(
                $admin,
                $password
            );
            $admin->setMdp($hashedPassword);
    
            // Gérez l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );
                $admin->setImage('/usersProfile/' . $fileName);
            }
    
            // Persistez et flush l'entité
            $entityManager->persist($admin);
            $entityManager->flush();
    
            $sid    = "AC55c30b554896dcd89fcb1e1123518694";
            $token  = "6c939dbc50fc916ef7c5285982939ae5";
            $twilio = new Client($sid, $token);
            
        
            $message = $twilio->messages
            
            ->create(
                "+21693588870",        [
            "from" => "+16812011941", // Votre numéro Twilio
            "body" => "Super Admin vous a ajouté comme admin à yumlink connectez vous via cet email: ".$admin->getEmail()." Mot de passe:Admin123? changez le  "
        ]
              
            );
            // Redirigez vers la page d'index des utilisateurs
            return $this->redirectToRoute('app_user_index');
        }
    
        // Rendez le formulaire dans le template
        return $this->renderForm('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }
    
}
