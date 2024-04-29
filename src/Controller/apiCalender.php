<?php

namespace App\Controller;

use App\Entity\Defis;
use App\Form\DefisType;
use App\Repository\DefisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime; 

class apiCalender extends AbstractController{
      /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'apiCalender',
        ]);
    }
     /**
     * @Route("/api/{id}/edit", name="api_defis_edit", methods={"PUT"})
     */
    public function majDefis(?Defis $defis, Request $request)
    {
        // Retrieve and decode the request data
        $donnees = json_decode($request->getContent());

        // Check if all required data is present
        if (
            isset($donnees->nomD) && !empty($donnees->nomD) &&
    
            isset($donnees->disD) && !empty($donnees->disD) 
            
        ) {
            // Data is complete
            // Initialize a response code
            $code = 200;

            // Check if the Defis entity exists
            if (!$defis) {
                // Instantiate a new Defis entity
                $defis = new Defis;

                // Change the response code
                $code = 201;
            }

            // Hydrate the entity with the data
            $defis->setNomD($donnees->nomD);
            $defis->setDisD($donnees->disD);
            $defis->setDelai(new DateTime($donnees->delai));
            $defis->setHeure(new DateTime($donnees->heure));

            // Optionally, handle additional properties if needed

            // Persist the changes to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($defis);
            $em->flush();

            // Return the response
            return new Response('Ok', $code);
        } else {
            // Data is incomplete
            return new Response('Incomplete data', 404);
        }
        return $this->render('api/index.html.twig', [
            'controller_name' => 'apiCalender',
        ]);
    }
}