<?php

namespace App\Src\Domain\Services\Enums;

use ReflectionClass;
use Illuminate\Support\Arr;

class UserRole
{
    const ADMINISTRATOR = 'administrator';
    const USER = 'user';

    static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
    
    static function getConstantsWithouAdmin()
    {
        return Arr::except(
            (new ReflectionClass(__CLASS__))->getConstants(),
            ['ADMINISTRATOR']
        );
    }
}
