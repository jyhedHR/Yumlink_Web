<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


#[Route('/article')]
class test extends AbstractController
{
#[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(Request $request, SecurityController $security): Response
    {
        // Get the user object from the session
         // Get the email from the session data
        $user = $security->getUser();
        $userid = $user->getIdU();
       

        return $this->render('test/index.html.twig', [
            'articles' => $userid,
      
        ]);
    }
}
