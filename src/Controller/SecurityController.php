<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AccountService;
use App\Form\ForgotPasswordType;
use App\Service\UploadFileService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        // if ($this->isGranted("ROLE_ADMIN")) {
        //     return $this->redirectToRoute('admin_login');
        // }

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
     
   

 #[Route(path: '/logout', name: 'app_logout')]
public function logout():void
{
}
#[Route('/forgot-password', name: 'app_forgot_password')]
public function forgotPassword( Request  $request): Response
{
    $form = $this->createForm(ForgotPasswordType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $account = $this->accountService->getAccountByEmail($data['email']);
        if($account)
        {
            $this->accountService->sendPasswordResetLink($account);
            $this->addFlash('success','Password reset link has been sent to your email');
            $email = '';
            return $this->render('security/reset_link_sent.html.twig',['email' => $email]);
        }
        else{
            $this->addFlash('error','Invalid email');
            return $this->render('security/forgot_password.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
    return $this->render('security/forgot_password.html.twig', [
        'form' => $form->createView()
    ]);
}

}
