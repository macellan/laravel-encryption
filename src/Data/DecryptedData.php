<?php

namespace Macellan\LaravelEncryption\Data;

/**
 * Decrypted Data Store
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class DecryptedData implements DecryptedDataInterface
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string|array|null
     */
    public function data()
    {
        return $this->data;
    }
}
