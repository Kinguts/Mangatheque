<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\Article;
use App\Repository\ArticleRepository;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/article/liste", name="liste", methods={"GET"})
     */
    public function liste(articleRepository $articleRepo)
    {
        //On récupère la liste des articles
        $article = $articleRepo->apiFindAll();

        //On spécifie qu'on utilise un encodeur en json
        $encoders = [new JsonEncoder()];

        //On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        //On fait la conversion en json
        //On instancie le convertiseur
        $serializer = new Serializer($normalizers, $encoders);

        //On convertit
        $jsonContent = $serializer->serialize($article, 'json', [
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
     * @Route("/article/find/{id}", name="find", methods={"GET"})
     */
    public function getArticle(Article $article)
    {    
        //On spécifie qu'on utilise un encodeur en json
        $encoders = [new JsonEncoder()];

        //On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        //On fait la conversion en json
        //On instancie le convertiseur
        $serializer = new Serializer($normalizers, $encoders);

        //On convertit
        $jsonContent = $serializer->serialize($article, 'json', [
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
     * Ajout d'un article
     * 
     * @Route("/article/add", name="add", methods={"POST"})
     */
    public function addArticle(Request $request)
    {
        //On vérifie si on à une requete XMLHttRequest
        if($request->isXMLHttpRequest()){
            //On vérifie les données après les avoir décodées
            $donnees = json_decode($request->getContent());

            //On instancie un nouvel article
            $article = new Article();

            //On hydrate l'article
            $article->setTitle($donnees->title);
            $article->setContent($donnees->content);
            $article->setFeaturedImage($donnees->image);
            $user = $this->getDoctrine()->getRepository(User::class)->find(2);
            $article->setUser($user);

            //On sauvegarde en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            //On retourne la confirmation
            return new Response('Ok', 201);
        }
        return new Response('Erreur', 404);
    }
    
}
