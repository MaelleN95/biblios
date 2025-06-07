<?php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Editor;
use DateTimeImmutable;
use App\Enum\BookStatus;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const BOOK_REFERENCE = 'book_';
    public const BOOK_COUNT = 33;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::BOOK_COUNT; $i++) {
            $book = new Book();

            $randomEditorReferenceIndex = $faker->numberBetween(0, EditorFixtures::EDITOR_COUNT - 1);
            $editor = $this->getReference(EditorFixtures::EDITOR_REFERENCE . $randomEditorReferenceIndex, Editor::class);

            $book->setEditor($editor);
            $book->setTitle($faker->realText(20));
            $book->setIsbn($faker->isbn13);
            $book->setCover('https://picsum.photos/200/300?random=' . $faker->unique()->numberBetween(1, 1000));

            $editedAt = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years', 'now'));
            $book->setEditedAt($editedAt);

            $book->setPlot($faker->realText(200));
            $book->setPageNumber($faker->numberBetween(70, 500));
            $book->setStatus($faker->randomElement(BookStatus::cases()));
    
            // Le livre doit être associé à au moins 1 auteur, au plus 3
            for ($j = 0; $j < rand(1,3); $j++) {
                $author = $this->getReference(
                    AuthorFixtures::AUTHOR_REFERENCE . $faker->numberBetween(0, AuthorFixtures::AUTHOR_COUNT - 1), Author::class
                );
                
                $book->addAuthor($author);
            }

            $book->setCreatedBy($this->getReference(UserFixtures::USER_REFERENCE . $faker->numberBetween(0, UserFixtures::USER_COUNT - 1), User::class));
            
            $manager->persist($book);

            $this->addReference(self::BOOK_REFERENCE . $i, $book);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EditorFixtures::class,
            AuthorFixtures::class,
            UserFixtures::class,
        ];
    }
}
