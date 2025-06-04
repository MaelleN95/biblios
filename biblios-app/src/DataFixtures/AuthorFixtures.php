<?php
namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;

class AuthorFixtures extends Fixture
{
    public const AUTHOR_REFERENCE = 'author_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 22; $i++) {
            $author = new Author();
            $author->setName($faker->name);

            // Conversion en DateTimeImmutable
            $dob = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-80 years', '-30 years'));
            $author->setDateOfBirth($dob);

            // dateOfDeath peut être null, donc on fait la conversion uniquement si non null
            $dodMutable = $faker->optional(0.3)->dateTimeBetween('-30 years', 'now');
            $dod = $dodMutable ? DateTimeImmutable::createFromMutable($dodMutable) : null;
            $author->setDateOfDeath($dod);

            $author->setNationality($faker->countryCode);

            $manager->persist($author);

            $this->addReference(self::AUTHOR_REFERENCE . $i, $author);
        }

        $manager->flush();
    }
}
