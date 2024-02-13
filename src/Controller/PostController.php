<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/post")]
class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route("/create", name: "create_post", methods: ["GET", "POST"])]
    public function createPostAction(Request $request): Response
    {
        // Create a new Post entity
        $post = new Post();

        $user = $this->getUser();

        // Check if a user is authenticated before setting the account
        if ($user) {
            $post->setAccount($user);
        } else {
            $this->addFlash('warning', 'You need to be logged in to create a post.');
            return $this->redirectToRoute('login'); // replace 'login' with your login route
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('profile_page');
        }

        return $this->render('new_post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route("/list-deleted", name:"list_delete_posts_request")]
    public function listDeleteRequestAction()
    {

        return $this->render('delete_request.html.twig');
    }

    #[Route("/post/{id}", name:"single_post", methods:["GET"])]
    public function getPostAction(int $id)
    {
        return $this->render('single_post.html.twig',['id' => $id]);
    }


    #[Route("/delete/{id}", name:"delete_post", methods:["GET", "POST"])]
    public function deletePostAction(int $id): Response
    {
        // Fetch the post by ID
        $post = $this->entityManager->getRepository(Post::class)->find($id);

        // Check if the post exists
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        // Check if the current user is the owner of the post or has the necessary permissions
        $user = $this->getUser();
        if ($user && ($user === $post->getAccount() || $this->isGranted('ROLE_ADMIN'))) {
            // Delete the post
            $this->entityManager->remove($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Post deleted successfully.');
        } else {
            // Handle the case where the user is not authorized to delete the post
            $this->addFlash('danger', 'You are not authorized to delete this post.');
        }

        // Redirect to the profile page or any other page after deleting the post
        return $this->redirectToRoute('profile_page');
    }

   
}