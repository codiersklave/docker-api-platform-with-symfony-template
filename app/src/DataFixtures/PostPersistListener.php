<?php

declare(strict_types=1);

namespace App\DataFixtures;

/**
 * Interface for a post persist listener for data fixtures.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
interface PostPersistListener
{
    /**
     * @param array $fixtureData
     * @param $entity
     */
    public function postPersist(array $fixtureData, $entity);
}
