<?php

declare(strict_types=1);

namespace App\Enum;

class Gender extends AbstractEnum
{
    use EnumMapTrait;
    
    public const GENDER_FEMALE = 'female';
    public const GENDER_MALE = 'male';
    public const GENDER_DIVERSE = 'diverse';
}
