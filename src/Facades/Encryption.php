<?php

namespace Macellan\LaravelEncryption\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Encryption Facade Accessor
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class Encryption extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'encryption';
    }
}
