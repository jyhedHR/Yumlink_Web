<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailerController extends AbstractController
{
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        // Créez un nouvel e-mail
        $email = (new Email())
            ->from('yumlink12@gmail.com') // Remplacez par votre adresse e-mail
            ->to('jihed.horchani@gmail.com') // Remplacez par l'adresse e-mail du destinataire
            ->subject('Test Email')
            ->text('Ceci est un e-mail de test.')
            ->html('<p>Ceci est un e-mail de test.</p>'); // Utilisez HTML pour envoyer du contenu riche

        // Envoyez l'e-mail
        $mailer->send($email);

        // Affichez une réponse pour confirmer que l'e-mail a été envoyé
        return new Response('E-mail envoyé avec succès !');
    }
}

