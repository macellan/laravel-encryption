<?php

namespace Macellan\LaravelEncryption\Adapters;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

/**
 * Encrypt Local Adapter using Laravel Encypter
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class LocalAdapter implements AdapterInterface, AdapterValidatorInterface, AdapterParamGeneratorInterface
{
    private Encrypter $encrypter;

    public function __construct(?string $encryptionKey, string $cipher = 'AES-256-CBC')
    {
        $this->encrypter = new Encrypter(self::parseKey($encryptionKey), $cipher);
    }

    public function encrypt($data): string
    {
        return $this->encrypter->encrypt($data);
    }

    public function decrypt(string $data)
    {
        return $this->encrypter->decrypt($data);
    }

    public static function parseKey(string $key): string
    {
        return Str::startsWith($key, $prefix = 'base64:') ? base64_decode(Str::after($key, $prefix)) : $key;
    }

    public static function validate(array $parameters): bool
    {
        try {
            new Encrypter(self::parseKey($parameters['encryptionKey']), $parameters['cipher']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function generate(array $parameters): array
    {
        if (! $parameters['encryptionKey']) {
            $parameters['encryptionKey'] = 'base64:'.base64_encode(Encrypter::generateKey($parameters['cipher']));
        }

        return $parameters;
    }
}
