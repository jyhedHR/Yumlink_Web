<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('Welcome');
    }
    public function onLoginSuccess()
    {
         return $this->redirectToRoute('homeOn');
    }
    #[Route('/', name: 'homeOn')]
    public function homeOn(): Response
    {$user = $this->getUser();
        if (!$user)
        {
            // Redirect to login if no user is authenticated
            return $this->redirectToRoute('app_login');
        }else
       
        $roles = $user->getRoles(); 
        $role = $roles[0]; 
    
        
        switch($role) 
        {
            case 'Client':
                return $this->render('user/ClientHome.html.twig');
            case 'Chef':
                return $this->render('user/ChefHome.html.twig');
            
            default:
            return $this->redirectToRoute('app_user_new'); 
            break;
        }
       
    }
    #[Route('/Welcome', name: 'Welcome')]
    public function Welcome(): Response
    {  return $this->render('Home.html.twig');}
    
}
