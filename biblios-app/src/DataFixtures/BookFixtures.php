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
            'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1496104679561-38a8ffea50b6?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1476958526483-36efcaa80a0b?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1473755504818-b72b28a90a13?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=200&q=80',
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
