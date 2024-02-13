<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Account;
use App\Service\PostService;
use App\Service\FollowService;
use App\Form\RegistrationFormType;
use App\Service\UploadFileService;
use App\Service\AccountDataService;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    private EntityManagerInterface $entityManager;
    /**
     * @var FollowService
     */
    private FollowService  $followService;

    public function __construct(PostService $postService, AccountDataService $accountDataService, FollowService $followService,EntityManagerInterface $entityManager)
    {
        $this->postService = $postService;
        $this->accountDataService = $accountDataService;
        $this->followService = $followService;
        $this->entityManager = $entityManager;

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

    #[Route(path: "/profile/delete", name: "profile_delete")]
    public function deleteProfile(EntityManagerInterface $entityManager): RedirectResponse
    {
        // Get the current user
        $user = $this->getUser();
    
        // Get the account entity for the current user
        $account = $entityManager->getRepository(Account::class)->findOneBy(['user' => $user]);
    
        if (!$account) {
            throw $this->createNotFoundException('Account not found.');
        }
    
        // Remove the account from the database
        $entityManager->remove($account);
        $entityManager->flush();
    
        // Add a flash message to indicate successful deletion
        $this->addFlash('success', 'Your profile has been deleted successfully.');
    
        // Redirect the user to a relevant page, such as the homepage
        return $this->redirectToRoute('homepage');
    }
    #[Route(path: "/account_list", name: "account_list")]

    public function listAction(EntityManagerInterface $entityManager): Response
    {
        $accounts = $entityManager->getRepository(Account::class)->findAll();

        return $this->render('account_list.html.twig',['accounts' => $accounts]);
    }
  
}
