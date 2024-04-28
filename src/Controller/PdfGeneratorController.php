<?php
 
 namespace App\Controller;

 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\HttpFoundation\Request;
 use Dompdf\Dompdf;
 
 class PdfGeneratorController extends AbstractController
 {
     #[Route('/pdf/generator', name: 'app_pdf_generator')]
     public function index( Request $request): Response
     {


        $totalCost = $request->query->get('totalCost');
        if ($totalCost === null) {
            // Handle the case where totalL is missing or invalid
            return new Response('Missing or invalid totalL parameter', Response::HTTP_BAD_REQUEST);
        }
         // Data to pass to the view
         $data = [
             'name'         => 'mohsen Doe',
             'address'      => 'USA',
             'Prix' => $totalCost,
             'email'        => 'jyhed.doe@email.com'
         ];
         
         // Generate HTML from Twig template
         $html = $this->renderView('pdf_generator/index.html.twig', $data);
         
         // Initialize Dompdf
         $dompdf = new Dompdf();
         $dompdf->loadHtml($html);
         
         // Render the PDF
         $dompdf->render();
         
         // Create a Response to trigger file download
         return new Response(
             $dompdf->stream('FACTURE.pdf', ["Attachment" => true]),
             Response::HTTP_OK,
             ['Content-Type' => 'application/pdf']
             
         );
         $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
       
     }
 
     private function imageToBase64($path) {
         $path = $path;
         $type = pathinfo($path, PATHINFO_EXTENSION);
         $data = file_get_contents($path);
         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
         return $base64;
     }
 }
 