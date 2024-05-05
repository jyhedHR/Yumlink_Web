<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAdminController extends AbstractController
{
   /* #[Route('/security/admin', name: 'app_security_admin')]
    public function index(): Response
    {
        return $this->render('security_admin/index.html.twig', [
            'controller_name' => 'SecurityAdminController',
        ]);
    }

    #[Route(path: '/loginadmin', name: 'app_admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('first_Page');
     }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_admin_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('/homeAdmin', name: 'first_Page')]
    public function back() : Response
    {
        return $this->render('admin/index.html.twig');
    }
 */   
}
