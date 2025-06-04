<?php

namespace App\DataFixtures;

use Dom\Comment;
use App\Factory\BookFactory;
use App\Factory\UserFactory;
use App\Factory\AuthorFactory;
use App\Factory\EditorFactory;
use App\Factory\CommentFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EditorFactory::createMany(20);
        AuthorFactory::createMany(10);
        UserFactory::createMany(5);
        BookFactory::createMany(50);
        CommentFactory::createMany(100);

        // Flush all changes to the database
        $manager->flush();
    }
}