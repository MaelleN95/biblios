<?php

namespace App\Factory;

use App\Entity\Author;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Author>
 */
final class AuthorFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Author::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->name(),
            'dateOfBirth' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'dateOfDeath' => self::faker()->optional(0.5)->dateTimeBetween('-30 years', 'now') ? \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-30 years', 'now')) : null,
            'nationality' => self::faker()->countryCode(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Author $author): void {})
        ;
    }
}
