<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\Serie;
use App\Repository\SerieRepository;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/serie/liste", name="liste", methods={"GET"})
     */
    public function liste(serieRepository $serieRepo)
    {
        //On récupère la liste des series
        $serie = $serieRepo->apiFindAll();      

        //On spécifie qu'on utilise un encodeur en json
        $encoders = [new JsonEncoder()];

        //On instancie le "normaliseur" pour convertir la serie en tableau
        $normalizers = [new ObjectNormalizer()];

        //On fait la conversion en json
        //On instancie le convertiseur
        $serializer = new Serializer($normalizers, $encoders);

        //On convertit
        $jsonContent = $serializer->serialize($serie, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        //On instancie la réponse
        $response = new Response($jsonContent);

        //On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        //On envoie la réponse
        return $response;
    }

    /**
     * @Route("/serie/find/{id}", name="find", methods={"GET"})
     */
    public function getCSerie(Serie $serie)
    {    
        //On spécifie qu'on utilise un encodeur en json
        $encoders = [new JsonEncoder()];

        //On instancie le "normaliseur" pour convertir la serie en tableau
        $normalizers = [new ObjectNormalizer()];

        //On fait la conversion en json
        //On instancie le convertiseur
        $serializer = new Serializer($normalizers, $encoders);

        //On convertit
        $jsonContent = $serializer->serialize($serie, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        //On instancie la réponse
        $response = new Response($jsonContent);

        //On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        //On envoie la réponse
        return $response;
    }

    /**
     * Ajout d'une serie
     * 
     * @Route("/serie/add", name="add", methods={"POST"})
     */
    public function addSerie(Request $request)
    {
        //On vérifie si on à une requete XMLHttRequest
        if($request->isXMLHttpRequest()){
            //On vérifie les données après les avoir décodées
            $donnees = json_decode($request->getContent());

            //On instancie une nouvelle serie
            $serie = new Serie();

            //On hydrate la serie
            $serie->setTitle($donnees->title);
            $serie->setContent($donnees->content);
            $serie->setFeaturedImage($donnees->image);
            $user = $this->getDoctrine()->getRepository(User::class)->find(2);
            $serie->setUser($user);

            //On sauvegarde en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($serie);
            $em->flush();

            //On retourne la confirmation
            return new Response('Ok', 201);
        }
        return new Response('Erreur', 404);
    }    
}
