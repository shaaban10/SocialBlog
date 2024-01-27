<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowerController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_follow')]
    public function follow(
        User $userToFollow,
        EntityManagerInterface $entityManager,
        Request $request 
    ): Response
    {
           /** @var User $currentUser */
           $currentUser = $this->getUser();

           // Vérifie si l'utilisateur à suivre n'est pas l'utilisateur actuel
           if ($userToFollow->getId() !== $currentUser->getId()) {
               // Appelle la méthode follow de l'utilisateur actuel pour suivre l'utilisateur spécifié
               $currentUser->follow($userToFollow);
               
               // Persiste les changements dans la base de données
               $entityManager->persist($currentUser);
               $entityManager->flush();
           }
   
           // Redirige l'utilisateur vers la page précédente
           return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{id}', name: 'app_unfollow')]
    public function unfollow(
        User $userToUnfollow,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
           /** @var User $currentUser */
           $currentUser = $this->getUser();

           // Vérifie si l'utilisateur à ne plus suivre n'est pas l'utilisateur actuel
           if ($userToUnfollow->getId() !== $currentUser->getId()) {
               // Appelle la méthode unfollow de l'utilisateur actuel pour ne plus suivre l'utilisateur spécifié
               $currentUser->unfollow($userToUnfollow);
               
               // Persiste les changements dans la base de données
               $entityManager->persist($currentUser);
               $entityManager->flush();
           }
   
           // Redirige l'utilisateur vers la page précédente
           return $this->redirect($request->headers->get('referer'));
    }
}
