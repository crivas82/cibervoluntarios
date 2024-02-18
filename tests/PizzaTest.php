<?php
// api/tests/PizzaTest.php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Pizza;
use App\Factory\PizzaFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class PizzaTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        $numberPizzas = 80;
        // Create X pizzas using the factory
        PizzaFactory::createMany($numberPizzas);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/pizzas');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Pizza',
            '@id' => '/api/pizzas',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $numberPizzas,
            'hydra:view' => [
                '@id' => '/api/pizzas?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/pizzas?page=1',
                'hydra:last' => '/api/pizzas?page=3',
                'hydra:next' => '/api/pizzas?page=2',
            ]
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Pizza::class);
    }


    public function testCreatePizza(): void
    {
        $headers = [
            'Content-Type' => 'application/ld+json',
        ];


        $response = static::createClient()->request('POST', '/api/pizzas', [
                'json' => [
                    "name" => "Pizza Test Cibervoluntarios",
                    "ingredients" => ["mozzarella","tomato","salami"],
                    "ovenTimeInSeconds" => 1200,
                    "special" => true
                ],
                'headers' => $headers
            ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@context" => "/api/contexts/Pizza",
            "@type" => "Pizza",
            "name" => "Pizza Test Cibervoluntarios",
            "ingredients" => ["mozzarella","tomato","salami"],
            "ovenTimeInSeconds" => 1200,
            "special" => true
        ]);
        $this->assertMatchesRegularExpression('~^/api/pizzas/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Pizza::class);
    }

    public function testCreateInvalidPizza(): void
    {
        $headers = [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json',
        ];

        static::createClient()->request('POST', '/api/pizzas', [
            'json' => [
                "ingredients" => [],
            ],
            'headers' => $headers
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $this->assertJsonContains([
            "@type" => "ConstraintViolationList",
            "status" => 422,
            "hydra:title" => "An error occurred",
            "hydra:description" => "name: This value should not be blank.\ningredients: You must specify at least one ingredient",
        ]);
    }

    public function testUpdatePizza(): void
    {
        // Only create the pizza we need with a name, ingredients, ...
        PizzaFactory::createOne([ "name" => "Pizza Test Cibervoluntarios",
            "ingredients" => ["mozzarella","tomato","salami"],
            "ovenTimeInSeconds" => 1200,
            "special" => true
        ]);

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $id = $this->findIriBy(Pizza::class, ['name' => 'Pizza Test Cibervoluntarios']);

        // Use the PATCH method here to do a partial update
        $response = $client->request('PATCH', $id, [
            'json' => [
                'name' => 'Pizza Test Cibervoluntarios new',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $id,
            'name' => 'Pizza Test Cibervoluntarios new'
        ]);

        $this->assertNotNull($response->toArray()['updatedAt']);
    }

    public function testDeletePizza(): void
    {
        // Only create the pizza we need with a name, ingredients, ...
        PizzaFactory::createOne([ "name" => "Pizza Test Cibervoluntarios",
            "ingredients" => ["mozzarella","tomato","salami"],
            "ovenTimeInSeconds" => 1200,
            "special" => true
        ]);

        $client = static::createClient();
        $id = $this->findIriBy(Pizza::class, ['name' => 'Pizza Test Cibervoluntarios']);

        $client->request('DELETE', $id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(Pizza::class)->findOneBy(["name" => "Pizza Test Cibervoluntarios"])
        );
    }
}
