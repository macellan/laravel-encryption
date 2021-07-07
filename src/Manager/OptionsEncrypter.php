<?php

namespace Macellan\LaravelEncryption\Manager;

use Macellan\LaravelEncryption\Adapters\AdapterInterface;
use Macellan\LaravelEncryption\Adapters\LocalAdapter;

/**
 * Encrypt Adapter Configuration
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class OptionsEncrypter
{
    public static AdapterInterface $adapter;

    public static function encrypt($data): string
    {
        if (config('encryption.options_encrypt')) {
            return self::adapter()->encrypt($data);
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public static function decrypt(string $payload, bool $encryption): array
    {
        if ($encryption) {
            return self::adapter()->decrypt($payload) ?? [];
        }

        return json_decode($payload, true, 512, JSON_THROW_ON_ERROR) ?? [];
    }

    public static function adapter(): AdapterInterface
    {
        return self::$adapter ??= new LocalAdapter(
            config('encryption.encryption_key')
        );
    }
}
