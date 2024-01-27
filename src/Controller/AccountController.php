<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Account;
use App\Service\PostService;
use App\Service\FollowService;
use App\Form\RegistrationFormType;
use App\Service\UploadFileService;
use App\Service\AccountDataService;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @var PostService
     */
    private PostService  $postService;
    /**
     * @var AccountDataService
     */
    private  AccountDataService  $accountDataService;
    /**
     * @var FollowService
     */
    private FollowService  $followService;

    public function __construct(PostService $postService, AccountDataService $accountDataService, FollowService $followService)
    {
        $this->postService = $postService;
        $this->accountDataService = $accountDataService;
        $this->followService = $followService;
    }

    #[Route(path: "/profile", name: "profile_page")]

    public function getProfile(Request $request, UploadFileService $uploadFileService): Response
    {
        $userAccount = $this->accountDataService->getUserData();
    
        // Get the user from the security context
        $user = $this->getUser();
    
        $form = $this->createForm(RegistrationFormType::class, $userAccount);
        $form->remove('plainPassword');
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $userPhoto = $form->get('userPhoto')->getData();
            if ($userPhoto) {
                $userPhotoName = $uploadFileService->upload($userPhoto, UploadFileService::AvatarType);
                $userAccount->setAvatar($userPhotoName);
            }
    
            $this->accountDataService->updateAccount($userAccount);
            $this->addFlash('success', 'Profile updated successfully');
        }
    
        return $this->render('profile.html.twig', [
            'account' => $userAccount,
            'user' => $user,  // Passing the user variable to the template
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: "/profile/{id}", name: "profile_page_view")]

    public function viewProfile(Account $account): Response
    {
        return $this->render('profile_view.html.twig', [
            'account' => $account,
        ]);
    }


    #[Route(path: "/account_list", name: "account_list")]

    public function listAction(): Response
    {
        return $this->render('account_list.html.twig');
    }
    
}
