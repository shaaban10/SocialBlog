<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminAccountController extends AbstractController
{

    
    #[Route(path:"login/admin",name:"admin_login")]
    public function loginAction()
    {
        return $this->render('admin.login.html.twig');
    }

}