<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;


class PostController extends AbstractController
{

    #[Route('/allposts', name: 'allposts')]
    public function allposts(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {        
        $posts = $postRepository->findAll(); 
        return $this->render('post/index.html.twig',
        ['posts' =>   $posts]);

    }

    #[Route('/post1/{author}', name: 'post1')]
    public function post1(EntityManagerInterface $entityManager,
    PostRepository $postRepository,$author): Response
    {        
        $where = [
            'author' => $author, 
         
        ];
        $order = [
            'created_at' => 'DESC', 
        ];

        $limit = 10; 
        $offset = 0; 

        $posts = $postRepository->findBy($where, $order, $limit, $offset);
        return $this->render('post/index.html.twig',
        ['posts' =>   $posts]);
    }
 
    #[Route('/addpost', name: 'addpost')]
    public function addpost(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {        
  
        // Création d'un nouvel objet post
        $post = new Post();

        // Définition des propriétés de l'objet
        $post->setTitle('Les fourmis');
        $post->setContent('Les fourmis sont des créatures fascinantes qui vivent en colonies très organisées et hiérarchisées. Elles peuvent être trouvées partout dans le monde, des forêts tropicales aux déserts arides en passant par les prairies et les villes.
         Chaque colonie de fourmis est dirigée par une reine qui pond des œufs et contrôle la production de phéromones, une substance chimique que les fourmis utilisent pour communiquer entre elles. Les fourmis sont connues pour leur travail acharné et leur dévouement en');
        $post->setAuthor("Meriem HNIDA");
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdateAt(new \DateTimeImmutable() );
        $post->setUrl('https://images.unsplash.com/photo-1611748939902-060e1ae99f32?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=814&q=80');
        $entityManager->persist($post);
        $entityManager->flush();
        
        // Envoyer un message de succès
        $this->addFlash('success', 'L\'enregistrement a été mis à jour avec succès.');
        $posts = $postRepository->findAll(); 
        return $this->render('post/index.html.twig',
        ['posts' =>   $posts]);
        
      
    }
        #[Route('/editpost', name: 'editpost')]
        public function editpost(EntityManagerInterface $entityManager,
        PostRepository $postRepository): Response
        {    

         $post = $postRepository->findByAuthor("Meriem Hnida"); 
      
        $post->setTitle('Les chats sont des animaux domestiques agréables');
        $entityManager->flush(); 
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig',
         ['posts' =>   $posts]);
      
    }

// Ajouter une nouvelle route pour ajouter des commentaires

#[Route('/AddComment', name: 'add')]
public function add(EntityManagerInterface $entityManager,
PostRepository $postRepository): Response
{        

    $post=$postRepository->find(1);   
    if($post)
    {
        $comment = new Comment();
        $comment->setContent('Commentaire 1');
        $comment->setAuthor('Meriem HNIDA');
        $comment->setDate(new \DateTimeImmutable());
        $category= new Category();
        $category->setName('Divers');
        $category->addPost($post);
        $post->addComment($comment);
        $entityManager->persist($post);
        $entityManager->persist($comment);
        $entityManager->persist($category);
        $entityManager->flush();
     }  
   

    $posts = $postRepository->findAll();
    return $this->render('post/index.html.twig',
     ['posts' =>   $posts]);
}

#[Route('/RemovePost', name: 'RemovePost')]
public function RemovePost(EntityManagerInterface $entityManager,
PostRepository $postRepository): Response
{        
    $post=$postRepository->find(1);   
    if($post)
    {
    $entityManager->remove($post);
    $entityManager->flush();
     }  
    $this->addFlash('success', 'le Post a été supprimé succès.');
    $posts = $postRepository->findAll();
    return $this->render('post/index.html.twig',
     ['posts' =>   $posts]);
}

    
}
