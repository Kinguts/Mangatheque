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


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\MangaRepository;
use App\Form\ArticleType;
use App\Form\MangaType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Manga;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/collections", name="collections")
     */
    public function index(EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage, PaginatorInterface $paginator)
    {

        $id = $tokenStorage->getToken()->getUser();
        $allArticles = $entityManager->getRepository(Article::class)->findBy(['user' => $id]);

        $article = $paginator->paginate(
            // Doctrine Query, not results
            $allArticles,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );

        return $this->render('blog/collections.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/collection/new", name="collection_create")
     * @Route("/collection/{id}/edit", name="collection_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) 
    {
        if(!$article) {
        $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        $this->getUser();  
        $article->setUser($this->getUser());  
        
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $form->handleRequest($request);    
        
        if($form->isSubmitted() && $form->isValid()) 
        {
            if(!$article->getId())
            {
            $article->setCreate_at(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('message', 'Votre manga à bien été ajouté');

            return $this->redirectToRoute('blog', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [ 'formArticle' => $form->createView(), 'editMode' => $article->getId() !== null, 'articles' => $articles
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
            $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
            $manga->setArticle($article);

            if(!$manga->getId())
            {
            $manga->setCreatedAt(new \DateTime());
            }

            $manager->persist($manga);
            $manager->flush();

            $this->addFlash('message', 'Votre manga à bien été ajouté');

            return $this->redirectToRoute('blog_show', ['id' => $id]);
        }

        return $this->render('blog/createManga.html.twig', [ 'formManga' => $form->createView()
        ]);
    }

    /**
     * @Route("/collection/{id}", name="collection_show")
     */
    public function show($id, Article $article, EntityManagerInterface $entityManager, Request $request, TokenStorageInterface $tokenStorage)
    {
        $userId = $tokenStorage->getToken()->getUser();
        $mangas = $entityManager->getRepository(Manga::class)->findBy(['user' => $userId, 'article' => $id]);  

        return $this->render('blog/show.html.twig', ['article' => $article, 'mangas' => $mangas
        ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete")
     * @return Response
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository( Article::class)->find($id);
        
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('message', 'Votre collection à bien été supprimé');

        return $this->redirectToRoute('blog');
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

        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/seinen", name="article_seinen")
     */
    public function seinen()
    {
        return $this->render('blog/seinen.html.twig');
    }

    /**
     * @Route("/shonen", name="article_shonen")
     */
    public function shonen()
    {
        return $this->render('blog/shonen.html.twig');
    }

    /**
     * @Route("/manhwa", name="article_manhwa")
     */
    public function manhwa()
    {
        return $this->render('blog/manhwa.html.twig');
    }

    /**
     * @Route("/allCollections", name="all_collections")
     */
    public function allCollections()
    {
        return $this->render('blog/allCollections.html.twig');
    }

    
    
}
