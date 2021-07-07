<?php

namespace Macellan\LaravelEncryption\Data;

use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Store Crypted Data
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class CryptedData implements CryptedDataInterface
{
    private EncryptionProvider $provider;

    private string $encryptedData;

    public function __construct(EncryptionProvider $provider, string $encryptedData)
    {
        $this->encryptedData = $encryptedData;
        $this->provider = $provider;
    }

    public function data(): string
    {
        return $this->encryptedData;
    }

    public function decrypt(): DecryptedDataInterface
    {
        return new DecryptedData($this->provider->getAdapter()->decrypt($this->encryptedData));
    }
}
