<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Encrypt Adapter Options
    |--------------------------------------------------------------------------
    */
    'options_encrypt' => true,

    /*
    |--------------------------------------------------------------------------
    | Options Encryption Key.
    |--------------------------------------------------------------------------
    */
    'encryption_key' => env('ENCRYPTION_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Adapters
    |--------------------------------------------------------------------------
    */
    'adapters' => [
        \Macellan\LaravelEncryption\Adapters\LocalAdapter::class,
    ],
];
