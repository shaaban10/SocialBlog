<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Service\PostService;
use App\Service\AccountDataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends  AbstractController
{
    private AccountDataService $accountDataService;
    private PostService $postService;
    private EntityManagerInterface $entityManager;

    
    public function __construct(AccountDataService $accountDataService,PostService  $postService,EntityManagerInterface  $entityManager)
    {
        $this->accountDataService = $accountDataService;
        $this->postService = $postService;
        $this->entityManager = $entityManager;

    }

   
    #[Route(path: "/comment/{id}", name: "add_comment", methods: ["POST"])]

    public function addCommentAction(Post $post, Request  $request):Response
    {
        $user = $this->accountDataService->getUserData();
        $commentText = $request->request->get('commentText');
       $comment =   $this->postService->commentOnPost($post, $user, $commentText);
        if ($request->isXmlHttpRequest())
        {
            return  $this->render('wrapper_comment.html.twig',['comment' => $comment]);
        }
        else {
            return $this->redirectToRoute('home_page');
        }
    }

    #[Route('/comment/{id}', name: 'delete_comment', methods: ['GET', 'POST', 'DELETE'])]
    public function deleteComment(Comment $comment): Response
    {
        $user = $this->getUser();

        // Check if the user is the owner of the comment or the owner of the associated post
        if ($user === $comment->getAccount() || $user === $comment->getPost()->getAccount()) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Comment deleted successfully.');

        }else{
            $this->addFlash('danger', 'You are not authorized to delete this comment.');

        }

        return $this->redirectToRoute('home_page');
    }
    #[Route('/comment/{id}/edit', name: 'edit_comment', methods: ['GET', 'POST'])]
    public function editComment(Request $request, Comment $comment): Response
    {
        $user = $this->getUser();
    
        // Check if the user is the owner of the comment
        if ($user !== $comment->getAccount()) {
            $this->addFlash('danger', 'You are not authorized to edit this comment.');
            return $this->redirectToRoute('home_page');
        }
    
        // Create a form to edit the comment
        $form = $this->createFormBuilder()
            ->add('commentText', TextareaType::class, [
                'label' => 'Edit your comment',
                'data' => $comment->getCommentText(),
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $commentText = $form->get('commentText')->getData();
            $comment->setCommentText($commentText);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Comment updated successfully.');
            return $this->redirectToRoute('home_page');
        }
    
        return $this->render('edit_comment.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

