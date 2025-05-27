<?php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Editor;
use DateTimeImmutable;
use App\Enum\BookStatus;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const BOOK_REFERENCE = 'book_';

   


    public function load(ObjectManager $manager): void
    {
        $coversUrl = [
            'https://images.unsplash.com/photo-1748324575258-b51559c5fefd?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw4fHx8ZW58MHx8fHx8',
            'https://plus.unsplash.com/premium_photo-1723830306042-8d90d4b18492?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxM3x8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1748285279219-4527c2daec9c?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwxMnx8fGVufDB8fHx8fA%3D%3D',
            'https://plus.unsplash.com/premium_photo-1746718185666-8596d35cf4d8?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyMXx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1748311380845-5c377561614d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyNHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1747995709691-5d0cf015c991?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwyN3x8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1748228876112-c5f37b99f77e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzMHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1748156783945-c8c585c403b3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzOHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1747863498866-e88b7ef50d3e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzNnx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1747903239211-ea42ad341368?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHw1NXx8fGVufDB8fHx8fA%3D%3D',
        ];

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 33; $i++) {
            $book = new Book();

            $editorReferenceIndex = $faker->numberBetween(0, 4);
            $editor = $this->getReference(EditorFixtures::EDITOR_REFERENCE . $editorReferenceIndex, Editor::class);

            $book->setEditor($editor);
            $book->setTitle($faker->realText(20));
            $book->setIsbn($faker->isbn13);
            $book->setCover($faker->randomElement($coversUrl));

            $editedAt = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years', 'now'));
            $book->setEditedAt($editedAt);

            $book->setPlot($faker->sentence(10, true));
            $book->setPageNumber($faker->numberBetween(100, 700));
            $book->setStatus($faker->randomElement(BookStatus::cases()));
            $manager->persist($book);

            $this->addReference(self::BOOK_REFERENCE . $i, $book);
        }

        // Association des auteurs aux livres
        for ($i = 0; $i < 33; $i++) {
            $book = $this->getReference(self::BOOK_REFERENCE. $i, Book::class);

            $nbAuthors = $faker->numberBetween(1, 4);
            $usedAuthors = [];
            for ($j = 0; $j < $nbAuthors; $j++) {
                $authorIndex = $faker->numberBetween(0, 21);
                if (!in_array($authorIndex, $usedAuthors)) {
                    $author = $this->getReference(AuthorFixtures::AUTHOR_REFERENCE . $authorIndex, Author::class);
                    $book->addAuthor($author); // méthode existante dans Book entity
                    $usedAuthors[] = $authorIndex;
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EditorFixtures::class,
            AuthorFixtures::class,
        ];
    }
}
