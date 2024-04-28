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
 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(EntityManagerInterface $entityManager , Request $request): Response
    {
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setIdClient(15); // Set client ID as needed
    
        // Persist the Commande entity to the database
        $entityManager->persist($commande);
        $entityManager->flush();
        $totalCost = $request->query->get('totalCost');
         return $this->redirectToRoute('app_pdf_generator', ['totalCost' => $totalCost], Response::HTTP_SEE_OTHER);
        
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
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
        $totalCost = $request->request->get('total_cost');
        if ($totalCost === null) {
            // Handle the case where totalL is missing or invalid
            return new Response('MHREZ', Response::HTTP_BAD_REQUEST);
        }
        return $this->redirectToRoute('app_stripe', ['totalCost' => $totalCost], Response::HTTP_SEE_OTHER);
    }
}