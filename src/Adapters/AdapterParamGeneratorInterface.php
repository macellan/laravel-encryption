<?php

namespace Macellan\LaravelEncryption\Adapters;

/**
 * Generate Adapter Construct Parameters
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
interface AdapterParamGeneratorInterface
{
    public static function generate(array $parameters): array;
}
