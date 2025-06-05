<?php
namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user_';
    public const USER_COUNT = 12;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();

            $user->setUsername($faker->userName);

            // Avec une probabilité de 70%, le role ROLE_ADMIN est mis le user
            $role = $faker->boolean(30) ? 'ROLE_ADMIN' : 'ROLE_USER';
            $user->setRoles([$role]);

            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);   
            $user->setEmail($faker->unique()->email);
            $user->setLastConnectedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));




            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }
}
