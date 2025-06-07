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
    public const USER_COUNT = 50;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();

            $user->setUsername($faker->userName);

            $role = $faker->randomElement(array_merge(
                array_fill(0, 50, 'ROLE_USER'),
                array_fill(0, 20, 'ROLE_BOOK_CREATE'),
                array_fill(0, 5, 'ROLE_MODERATOR'),
                array_fill(0, 15, 'ROLE_BOOK_EDIT'),
                array_fill(0, 10, 'ROLE_ADMIN'),
            ));
            $user->setRoles([$role]);

            $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
            
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user->setFirstName($firstName);
            $user->setLastName($lastName);

            $emailPatterns = [
                strtolower($firstName) . '.' . strtolower($lastName) . '@' . $faker->freeEmailDomain,
                strtolower($firstName[0]) . strtolower($lastName) . '@' . $faker->safeEmailDomain,
                strtolower($lastName) . $faker->numberBetween(1, 99) . '@example.com',
                strtolower($firstName) . $faker->numberBetween(10, 9999) . '@' . $faker->domainName,
            ];

            $user->setEmail($faker->unique()->randomElement($emailPatterns));

            $usernamePatterns = [
                strtolower($firstName) . $faker->numberBetween(1, 999),
                strtolower($firstName[0]) . strtolower($lastName),
                strtolower($lastName) . '_' . strtolower($faker->word),
                strtolower($firstName) . '_' . strtolower($lastName),
                strtolower($firstName) . '.' . strtolower($lastName),
                strtolower($lastName) . '.' . $faker->numberBetween(1, 99),
                strtolower($firstName) . $faker->numberBetween(100, 9999),
                strtolower($lastName) . $faker->numberBetween(1, 9999),
                strtolower($firstName) . '_' . $faker->word,
                strtolower($lastName) . '_' . $faker->word,
                strtolower($firstName) . $faker->userName,
                strtolower($lastName) . $faker->userName,
                $faker->userName,
            ];

            $user->setUsername($faker->unique()->randomElement($usernamePatterns));

            $user->setLastConnectedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));

            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }
}
