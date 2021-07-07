<?php

namespace Macellan\LaravelEncryption\Data;

/**
 * Crypted Data Interface
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
interface CryptedDataInterface
{
    public function data(): string;

    public function decrypt(): DecryptedDataInterface;
}
