<?php

namespace App\Factory;

use App\Entity\Pizza;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Pizza>
 *
 * @method        Pizza|Proxy                      create(array|callable $attributes = [])
 * @method static Pizza|Proxy                      createOne(array $attributes = [])
 * @method static Pizza|Proxy                      find(object|array|mixed $criteria)
 * @method static Pizza|Proxy                      findOrCreate(array $attributes)
 * @method static Pizza|Proxy                      first(string $sortedField = 'id')
 * @method static Pizza|Proxy                      last(string $sortedField = 'id')
 * @method static Pizza|Proxy                      random(array $attributes = [])
 * @method static Pizza|Proxy                      randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static Pizza[]|Proxy[]                  all()
 * @method static Pizza[]|Proxy[]                  createMany(int $number, array|callable $attributes = [])
 * @method static Pizza[]|Proxy[]                  createSequence(iterable|callable $sequence)
 * @method static Pizza[]|Proxy[]                  findBy(array $attributes)
 * @method static Pizza[]|Proxy[]                  randomRange(int $min, int $max, array $attributes = [])
 * @method static Pizza[]|Proxy[]                  randomSet(int $number, array $attributes = [])
 */
final class PizzaFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function getDefaults(): array
    {
        $arrayIngredients = [
            'tomato','bacon','chicken','roquefort','parmesano',
            'onion','tuna','pepper','olive oils','burguer meat',
            'fresh basil leaves', 'pepperoni slices', 'ham', 'pineapple chunks',
            'red onion slices','black olives', 'ground beef','BBQ sauce', 'cilantro leaves',
            'ricotta cheese', 'spinach leaves', 'sausage', 'parmesano', 'cheddar', 'gouda',
            'provolone', 'cherry tomatoes', 'oregano', 'salami', 'anchovies', 'eggs', 'blue cheese',
            'grilled chicken', 'feta', 'caramelized onions', 'truffle oil'
        ];


        return [
            'ingredients' => array_merge(['mozzarella'], self::faker()->randomElements($arrayIngredients, random_int(1,19), false)),
            'name' => 'Pizza '.self::faker()->text(42),
            'ovenTimeInSeconds' => random_int(600, 1800),
            'special' => self::faker()->boolean(),
            'updatedAt' => new \DateTime('now')
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Pizza $pizza): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Pizza::class;
    }
}
