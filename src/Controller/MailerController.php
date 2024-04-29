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
        // Create a new email
        $email = (new Email())
        ->from('yumlink12@gmail.com') 
        ->to('jihedhorchani1234@gmail.com') 
        //->cc('exemple@mail.com') 
        //->bcc('exemple@mail.com')
        //->replyTo('test42@gmail.com')
            ->priority(Email::PRIORITY_HIGH) 
            ->subject('Reclamation')
        // If you want use text mail only
            ->text(' Reclamation a envoyer avec succes ') 
        ;

        // Try to send the email
        try {
            $mailer->send($email);
            // If the email was sent successfully, return a success response
            return new Response('Email sent successfully!');
        } catch (\Exception $e) {
            // If there was an error, return an error response with the error message
            return new Response('Error sending email: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

