<?php
namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use DateTimeImmutable;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de 50 commentaires aléatoires liés aux livres
        for ($i = 0; $i < 50; $i++) {
            $comment = new Comment();

            // Choisir un livre aléatoire
            $bookIndex = $faker->numberBetween(0, 32);
            $book = $this->getReference(BookFixtures::BOOK_REFERENCE . $bookIndex, Book::class);

            $comment->setBook($book);
            $comment->setName($faker->name);
            $comment->setEmail($faker->email);

            // Convertir DateTime en DateTimeImmutable
            $createdAt = DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('-2 years', 'now')
            );
            $comment->setCreatedAt($createdAt);

            // Faker optional prend 2 arguments depuis la version 1.9 (probabilité + callable)
            $publishedAtDateTime = $faker->optional(0.7, function() use ($faker) {
                return DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now'));
            });

            $publishedAtDateTimeMutable = $faker->optional(0.7)->dateTimeBetween('-1 years', 'now');
            $publishedAtDateTime = $publishedAtDateTimeMutable 
                ? DateTimeImmutable::createFromMutable($publishedAtDateTimeMutable) 
                : null;

            $comment->setPublishedAt($publishedAtDateTime);

            $comment->setStatus($faker->randomElement(['approved', 'pending', 'rejected']));
            $comment->setContent($faker->sentence(2, true));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    // Ajouter le type de retour array pour être compatible avec l'interface
    public function getDependencies(): array
    {
        return [
            BookFixtures::class,
        ];
    }
}
