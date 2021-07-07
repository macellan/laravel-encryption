<?php

namespace Macellan\LaravelEncryption\Manager;

use Macellan\LaravelEncryption\Data\CryptedData;
use Macellan\LaravelEncryption\Data\CryptedDataInterface;
use Macellan\LaravelEncryption\Data\DecryptedDataInterface;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Encryption Manager
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class EncryptionManager implements ManagerInterface
{
    private EncryptionProvider $provider;

    public function __construct(EncryptionProvider $provider)
    {
        $this->provider = $provider;
    }

    public function encrypt($data): CryptedDataInterface
    {
        return new CryptedData($this->provider, $this->provider->getAdapter()->encrypt($data));
    }

    public function decrypt(CryptedDataInterface $payload): DecryptedDataInterface
    {
        return $payload->decrypt();
    }

    public function getProvider(): EncryptionProvider
    {
        return $this->provider;
    }
}
