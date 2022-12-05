<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }
    public function load(ObjectManager $manager): void
    {
    
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '12345678'
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@test.com');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                '12345678'
            )
        );
        $manager->persist($user2);

        $news = new News();
        $news->setTitle('Welcome to US!');
        $news->setContent('Welcome to US!');
        $news->setDateCreated(new DateTime());
        $news->setAuthor("James Bond");
        $news->setLink("www.example.com");
        $news->setDescription("This is a test description");
        $manager->persist($news);

        $manager->flush();
    }
}
