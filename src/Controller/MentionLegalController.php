<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionLegalController extends AbstractController
{

    #[Route('/mention/legal', name: 'app_mention_legal')]
    public function index(): Response
    {
                $account = $this->getUser(); // Assuming you have the user account available via security
        return $this->render('mention_legal/index.html.twig', [
            'account' => $account,
        ]);
    }
}
