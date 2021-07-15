<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CheeseListing;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CheeseListingFixture extends AbstractFixture implements DependentFixtureInterface
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

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}