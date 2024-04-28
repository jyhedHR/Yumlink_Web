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
        $entityManager = $this->getDoctrine()->getManager();
    
        $qb = $entityManager->createQueryBuilder();
        $qb->select('r')
           ->from('App\Entity\RatingRecettes', 'r')
           ->where('r.recette = :recetteId')
           ->setParameter('recetteId', $recetteId);
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
    
        
        $totalStars = ($totalFiveStarRatings * 5) + ($totalFourStarRatings * 4) + ($totalThreeStarRatings * 3) + ($totalTwoStarRatings * 2) + $totalOneStarRatings;
        $averageRating = $totalRatings > 0 ? $totalStars / $totalRatings : 0;
    
        $totalStarsPercentage = $totalRatings > 0 ? ($totalStars / ($totalRatings * 5)) * 100 : 0;
        $fiveStarProgress = $totalRatings > 0 ? ($totalFiveStarRatings / $totalRatings) * 100 : 0;
        $fourStarProgress = $totalRatings > 0 ? ($totalFourStarRatings / $totalRatings) * 100 : 0;
        $threeStarProgress = $totalRatings > 0 ? ($totalThreeStarRatings / $totalRatings) * 100 : 0;
        $twoStarProgress = $totalRatings > 0 ? ($totalTwoStarRatings / $totalRatings) * 100 : 0;
        $oneStarProgress = $totalRatings > 0 ? ($totalOneStarRatings / $totalRatings) * 100 : 0;
    
    
        $responseData = [
            'average_rating' => number_format($averageRating, 1),
            'total_review' => $totalRatings,
            'total_five_star_review' => $totalFiveStarRatings,
            'total_four_star_review' => $totalFourStarRatings,
            'total_three_star_review' => $totalThreeStarRatings,
            'total_two_star_review' => $totalTwoStarRatings,
            'total_one_star_review' => $totalOneStarRatings,
            'five_star_progress' => $fiveStarProgress,
            'four_star_progress' => $fourStarProgress,
            'three_star_progress' => $threeStarProgress,
            'two_star_progress' => $twoStarProgress,
            'one_star_progress' => $oneStarProgress,
        ];
       dump($totalStars);
       dump($averageRating) ;
       
        return new JsonResponse($responseData);
}
}
