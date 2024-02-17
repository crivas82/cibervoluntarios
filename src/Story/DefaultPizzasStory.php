<?php

namespace App\Story;

use App\Factory\PizzaFactory;
use Zenstruck\Foundry\Story;

final class DefaultPizzasStory extends Story
{
    public function build(): void
    {
        PizzaFactory::createMany(80);
    }
}
