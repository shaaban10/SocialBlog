<?php

namespace App\DataFixtures;

use App\Factory\AccountFactory;
use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixture extends  Fixture
{
    public function load(ObjectManager $manager): void
    {
        AccountFactory::createMany(20,['posts' => PostFactory::new()->many(10)]);
        $manager->flush();
    }
    public function loadAdmin(ObjectManager $manager): void
    {
        AccountFactory::createMany(5);
        $manager->flush();
    }
}