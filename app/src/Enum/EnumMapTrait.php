<?php

declare(strict_types=1);

namespace App\Enum;

use ReflectionClass;

trait EnumMapTrait
{
    public static function getEnumMap(): array
    {
        $ref = new ReflectionClass(self::class);
        return $ref->getConstants();
    }
}
