<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CheeseListing;

class CheeseListingFixture extends AbstractFixture
{
    protected function getEntityClass(): string
    {
        return CheeseListing::class;
    }

    protected function getFixtureFiles(): array
    {
        return [
            'cheeses.csv',
        ];
    }
}