<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Form\CommandeType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(EntityManagerInterface $entityManager , Request $request,SecurityController $session ): Response
    {
        $user = $session->getUser();
        $id=$user->getIdu();
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setIdClient($id); // Set client ID as needed
    
        // Persist the Commande entity to the database
        $entityManager->persist($commande);
        $entityManager->flush();
        $totalCost = $request->query->get('totalCost');
         return $this->redirectToRoute('app_pdf_generator', ['totalCost' => $totalCost], Response::HTTP_SEE_OTHER);
        
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, MailerInterface $mailer)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        $email = (new Email())
        ->from('yumlink12@gmail.com') 
        ->to('jihedhorchani1234@gmail.com') 
        //->cc('exemple@mail.com') 
        //->bcc('exemple@mail.com')
        //->replyTo('test42@gmail.com')
            ->priority(Email::PRIORITY_HIGH) 
            ->subject('Payment')
        // If you want use text mail only
            ->text(' Payment Successful!
            thak youu <3  ') 
        ;
         // Try to send the email
         $mailer->send($email);

        $totalCost = $request->request->get('total_cost');
        if ($totalCost === null) {
            // Handle the case where totalL is missing or invalid
            return new Response('MHREZ', Response::HTTP_BAD_REQUEST);
        }
        return $this->redirectToRoute('app_stripe', ['totalCost' => $totalCost], Response::HTTP_SEE_OTHER);
    }
}