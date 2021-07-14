<?php

declare(strict_types=1);

namespace App\Enum;

abstract class AbstractEnum
{
    /**
     * @param string $value
     * @return int|null
     */
    public static function getKey(string $value): ?int
    {
        [$key] = self::getKeys([$value]);
        return $key;
    }
    
    /**
     * @param int $key
     * @return string|null
     */
    public static function getValue(int $key): ?string
    {
        return (static::getEnumMap()[$key] ?? null);
    }
    
    /**
     * @param array $values
     * @return array
     */
    public static function getKeys(array $values): array
    {
        $keys = array_flip(static::getEnumMap());
        return array_map(
            static function (?string $value) use ($keys) {
                return ($keys[$value] ?? null);
            },
            $values
        );
    }
    
    /**
     * @return array
     */
    abstract public static function getEnumMap(): array;
}
