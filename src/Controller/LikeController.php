<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(Post $post, PostRepository $posts,Request $request,EntityManagerInterface $manager): Response
    {
        $currentUser = $this->getUser();
        $post->addLikedBy($currentUser);

        $manager->persist($post);
        $manager->flush();
        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/unlike/{id}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(Post $post,PostRepository $posts,Request $request,EntityManagerInterface $manager): Response
    {
        $currentUser = $this->getUser();
        $post->removeLikedBy($currentUser);
        $manager->persist($post);
        $manager->flush();     
           return $this->redirect($request->headers->get('referer'));

    }
}
