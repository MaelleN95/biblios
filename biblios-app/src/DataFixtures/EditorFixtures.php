<?php
namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EditorFixtures extends Fixture
{
    public const EDITOR_REFERENCE = 'editor_';
    public const EDITOR_COUNT = 12;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::EDITOR_COUNT; $i++) {
            $editor = new Editor();

            $editor->setName($faker->company);

            $manager->persist($editor);

            $this->addReference(self::EDITOR_REFERENCE . $i, $editor);
        }

        $manager->flush();
    }
}
