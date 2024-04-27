<?php

namespace App\Controller;

use App\Entity\RatingRecettes;
use App\Entity\Recettes;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class RatingRecettesController extends AbstractController
{
    #[Route('/rating/recettes', name: 'app_rating_recettes')]
    public function index(): Response
    {
        return $this->render('rating_recettes/index.html.twig', [
            'controller_name' => 'RatingRecettesController',
        ]);
    }

    #[Route('/rating/submit_rating', name: 'submit_rating', methods: ['POST'])]
    public function submitRating(Request $request): Response
    {
        $ratingData = $request->request->get('rating_data');
        $recetteId = $request->request->get('recette_id'); 
        $rating = new RatingRecettes();
        $rating->setRating($ratingData);
       
        $entityManager = $this->getDoctrine()->getManager(); 
        $recette = $entityManager->getRepository(Recettes::class)->find($recetteId);
        $rating->setRecette($recette);
        $rating->setCreatedAt(new \DateTime());
      
        $entityManager->persist($rating);
        $entityManager->flush();
        return new Response('Rating submitted successfully!', Response::HTTP_OK);
    }

     
    #[Route('/load_rating_data', name: 'load_rating_data', methods: ['POST'])]
    public function loadRating(Request $request): Response
    {
        $recetteId = $request->request->get('recette_id');
        dump($recetteId) ; 
        // Get the EntityManager
        $entityManager = $this->getDoctrine()->getManager();
    
        // Retrieve all ratings for the given Recette ID
        //$ratings = $entityManager->getRepository(RatingRecettes::class)->findBy(['recette' => $recetteId]);
       /* $ratings = [
            ['rating' => 5],
            ['rating' => 4],
            ['rating' => 3],
            ['rating' => 2],
            ['rating' => 1],
        ];*/
        $qb = $entityManager->createQueryBuilder();
        $qb->select('r')
           ->from('App\Entity\RatingRecettes', 'r')
           ->where('r.recette = :recetteId')
           ->setParameter('recetteId', $recetteId);
    
        // Execute the query
        $ratings = $qb->getQuery()->getResult();
        dump($ratings);
        
        $totalRatings = count($ratings);
        $totalFiveStarRatings = $totalFourStarRatings = $totalThreeStarRatings = $totalTwoStarRatings = $totalOneStarRatings = 0;
    
        foreach ($ratings as $rating) {
            switch ($rating->getRating()) {
                case 5:
                    $totalFiveStarRatings++;
                    break;
                case 4:
                    $totalFourStarRatings++;
                    break;
                case 3:
                    $totalThreeStarRatings++;
                    break;
                case 2:
                    $totalTwoStarRatings++;
                    break;
                case 1:
                    $totalOneStarRatings++;
                    break;
            }
        }
    
        // Calculate average rating
        $totalStars = ($totalFiveStarRatings * 5) + ($totalFourStarRatings * 4) + ($totalThreeStarRatings * 3) + ($totalTwoStarRatings * 2) + $totalOneStarRatings;
        $averageRating = $totalRatings > 0 ? $totalStars / $totalRatings : 0;
    
        // Prepare response data
        $responseData = [
            'average_rating' => number_format($averageRating, 1),
            'total_review' => $totalRatings,
            'total_five_star_review' => $totalFiveStarRatings,
            'total_four_star_review' => $totalFourStarRatings,
            'total_three_star_review' => $totalThreeStarRatings,
            'total_two_star_review' => $totalTwoStarRatings,
            'total_one_star_review' => $totalOneStarRatings,
        ];
       dump($totalStars);
       dump($averageRating) ;
        // Return the response as JSON
        return new JsonResponse($responseData);
}
}
