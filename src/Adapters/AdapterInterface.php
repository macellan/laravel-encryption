<?php

namespace Macellan\LaravelEncryption\Adapters;

/**
 * Encryption Adapter Interface
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
interface AdapterInterface
{
    /**
     * Encrypt Data
     *
     * @param array|string $data
     */
    public function encrypt($data): string;

    /**
     * Decrypt Data
     *
     * @param string $data
     *
     * @return mixed
     */
    public function decrypt(string $data);
}
