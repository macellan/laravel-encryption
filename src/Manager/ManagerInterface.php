<?php

namespace Macellan\LaravelEncryption\Manager;

use Macellan\LaravelEncryption\Data\CryptedDataInterface;
use Macellan\LaravelEncryption\Data\DecryptedDataInterface;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Encryption Manager Interface
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
interface ManagerInterface
{
    /**
     * @param string|array $data
     */
    public function encrypt($data): CryptedDataInterface;

    public function decrypt(CryptedDataInterface $payload): DecryptedDataInterface;

    public function getProvider(): EncryptionProvider;
}
