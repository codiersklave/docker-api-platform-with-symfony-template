<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;

class UserFixture extends AbstractFixture
{
    protected function getEntityClass(): string
    {
        return User::class;
    }

    protected function getFixtureFiles(): array
    {
        return [
            'users.yml',
        ];
    }
}