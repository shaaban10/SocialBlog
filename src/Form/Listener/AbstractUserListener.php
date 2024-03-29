<?php

namespace App\Listener;

use App\Entity\User;
use App\Service\PasswordHasherService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class AbstractUserListener
{
    private PasswordHasherService $passwordHasher;

    public function __construct(PasswordHasherService $passwordHasher)
 {
     $this->passwordHasher = $passwordHasher;
 }

    public function __invoke(User $user , LifecycleEventArgs  $args)
    {
        $user->setPassword($this->passwordHasher->hashPassword ($user->getPassword()));
    }
}