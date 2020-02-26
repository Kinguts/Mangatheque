<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\User;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use App\Repository\MangaRepository;
use App\Form\SerieType;
use App\Form\MangaType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Manga;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/series", name="series")
     */
    public function index(EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage, PaginatorInterface $paginator)
    {
        $currentUserId = $tokenStorage->getToken()->getUser();
        $user = $entityManager->getRepository(User::class)->find($currentUserId);

        if (isset($user)) {

            $allSeries = $user->getSeries();
            
        } else {
            $allSeries = array();           
        } 
        $serie = $paginator->paginate(
            // Doctrine Query, not results
            $allSeries,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            6
        );
        return $this->render('mangatheque/series.html.twig', [
            'serie' => $serie
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('mangatheque/home.html.twig');
    }

    /**
     * @Route("/serie/new", name="serie_create")
     * @Route("/serie/{id}/edit", name="serie_edit")
     */
    public function form(Serie $serie = null, Request $request, ObjectManager $manager) 
    {
        if(!$serie) {
        $serie = new Serie();
        }

        $form = $this->createForm(SerieType::class, $serie);
        $this->getUser();  
        $serie->addUser($this->getUser());  
        
        $series = $this->getDoctrine()->getRepository(Serie::class)->findAll();

        $form->handleRequest($request);    
        
        if($form->isSubmitted() && $form->isValid()) 
        {
            if(!$serie->getId())
            {
            $serie->setCreate_at(new \DateTime());
            }

            $manager->persist($serie);
            $manager->flush();

            $this->addFlash('message', 'Votre manga à bien été ajouté');

            return $this->redirectToRoute('series', ['id' => $serie->getId()]);
        }
        return $this->render('mangatheque/create.html.twig', [ 'formSerie' => $form->createView(), 'editMode' => $serie->getId() !== null, 'series' => $series
        ]);
    }

    /**
     * @Route("/createManga", name="create_manga")
     */
    public function formManga(Manga $manga = null, Request $request, EntityManagerInterface $manager) 
    {
        if(!$manga) {
        $manga = new Manga();
        }

        $form = $this->createForm(MangaType::class, $manga); 

        $form->handleRequest($request);   
        
        if($form->isSubmitted() && $form->isValid()) 
        {
            $this->getUser();  
            $manga->setUser($this->getUser());

            $id = $request->query->get('id');
            $serie = $this->getDoctrine()->getRepository(Serie::class)->find($id);
            $manga->setSerie($serie);

            if(!$manga->getId())
            {
            $manga->setCreatedAt(new \DateTime());
            }
            $manager->persist($manga);
            $manager->flush();

            $this->addFlash('message', 'Votre manga à bien été ajouté');

            return $this->redirectToRoute('serie_show', ['id' => $id]);
        }
        return $this->render('mangatheque/createManga.html.twig', [ 'formManga' => $form->createView()
        ]);
    }

    /**
     * @Route("/serie/{id}/update", name="serie_update")
     */
    public function formUpdate($id, Request $request, ObjectManager $manager) 
    {
        $serie = $this->getDoctrine()->getRepository(Serie::class)->find($id);

        if(!$serie) {
            $this->addFlash('message', 'Un problème est survenu');
            return $this->redirectToRoute('serie_create');
        } 
        $form = $this->createForm(SerieType::class, $serie);
        $serie->addUser($this->getUser());  

        $form->handleRequest($request);  
        
        if(!$serie->getId()) {
            $serie->setCreate_at(new \DateTime());
        }
        $manager->persist($serie);
        $manager->flush();

        $this->addFlash('message', 'Votre manga à bien été ajouté');

        return $this->redirectToRoute('series', ['id' => $serie->getId()]);        
    }
  
    /**
     * @Route("/serie/{id}", name="serie_show")
     */
    public function show($id, Serie $serie, EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage)
    {
        $userId = $tokenStorage->getToken()->getUser();
        $mangas = $entityManager->getRepository(Manga::class)->findBy(['user' => $userId, 'serie' => $id]);  

        return $this->render('mangatheque/show.html.twig', ['serie' => $serie, 'mangas' => $mangas
        ]);
    }

    /**
     * @Route("/delete/{id}", name="serie_delete")
     * @return Response
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $serie = $entityManager->getRepository( Serie::class)->find($id);
        
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('message', 'Votre serie à bien été supprimé');

        return $this->redirectToRoute('series');
    }

    /**
     * @Route("/delete/manga/{id}", name="manga_delete")
     * @return Response
     */
    public function deleteManga($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $manga = $entityManager->getRepository( Manga::class)->find($id);
        
        $entityManager->remove($manga);
        $entityManager->flush();

        $this->addFlash('message', 'Votre manga à bien été supprimé');

        return $this->redirectToRoute('series');
    }

    /**
     * @Route("/seinen", name="serie_seinen")
     */
    public function seinen()
    {
        return $this->render('mangatheque/seinen.html.twig');
    }

    /**
     * @Route("/shonen", name="serie_shonen")
     */
    public function shonen()
    {
        return $this->render('mangatheque/shonen.html.twig');
    }

    /**
     * @Route("/manhwa", name="serie_manhwa")
     */
    public function manhwa()
    {
        return $this->render('mangatheque/manhwa.html.twig');
    }

    /**
     * @Route("/allSeries", name="all_series")
     */
    public function allSeries()
    {
        return $this->render('mangatheque/allSeries.html.twig');
    }       
}
