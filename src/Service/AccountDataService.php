<?php

namespace App\Service;

use App\Contract\UploadFileInterface;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountDataService
{
    private UploadFileInterface $uploadFile;
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        UploadFileInterface $uploadFile,
        AccountRepository $accountRepository,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->uploadFile = $uploadFile;
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getUserData(): ?Account
    {
        // Get the currently authenticated user from the token storage
        $user = $this->tokenStorage->getToken()->getUser();

        // Check if the user is an instance of Account
        if ($user instanceof Account) {
            // Fetch the account based on the user's ID
            return $this->accountRepository->find($user->getId());
        }

        return null;
    }

    public function updateAccount(Account $account)
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
