<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;

class PersonFixture extends AbstractFixture
{
    protected function getEntityClass(): string
    {
        return Person::class;
    }

    protected function getFixtureFiles(): array
    {
        return [
            'females.yml',
            'males.yml',
        ];
    }
}