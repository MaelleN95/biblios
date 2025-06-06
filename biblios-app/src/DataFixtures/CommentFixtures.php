<?php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{   
    public const COMMENT_COUNT = 50;
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::COMMENT_COUNT; $i++) {
            $comment = new Comment();

            // Choisir un livre aléatoire
            $bookIndex = $faker->numberBetween(0, BookFixtures::BOOK_COUNT - 1);
            $book = $this->getReference(BookFixtures::BOOK_REFERENCE . $bookIndex, Book::class);

            // Choisir un utilisateur aléatoire
            $userIndex = $faker->numberBetween(0, UserFixtures::USER_COUNT - 1);
            $user = $this->getReference(UserFixtures::USER_REFERENCE . $userIndex, User::class);

            $comment->setBook($book);
            $comment->setUser($user);

            // Convertir DateTime en DateTimeImmutable
            $createdAt = DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('-2 years', 'now')
            );
            $comment->setCreatedAt($createdAt);

            $publishedAtDateTime = $faker->optional(0.7, function() use ($faker) {
                return DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now'));
            });

            $publishedAtDateTimeMutable = $faker->optional(0.7)->dateTimeBetween('-1 years', 'now');
            $publishedAtDateTime = $publishedAtDateTimeMutable
                ? DateTimeImmutable::createFromMutable($publishedAtDateTimeMutable)
                : null;

            $comment->setPublishedAt($publishedAtDateTime);

            $comment->setStatus($faker->randomElement(['approved', 'pending', 'rejected']));
            $comment->setContent($faker->realText(100));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookFixtures::class,
            UserFixtures::class,
        ];
    }
}
