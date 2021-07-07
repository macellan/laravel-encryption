<?php

namespace Macellan\LaravelEncryption\Adapters;

/**
 * Validate Adapter Configuration
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
interface AdapterValidatorInterface
{
    public static function validate(array $parameters): bool;
}
