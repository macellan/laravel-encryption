
Laravel Encryption
=========

Provides flexible data encryption adapter for Laravel. 2-step encryption is available. 
Adapter settings are encrypted with "encryption_key". Data is encrypted with adapters.   

Requirement PHP 7.4+.  

Installation
--------------------
* Install Package
    ```shell
    composer req macellan/laravel-encryption
    ```
* Configuration: __config/encryption.php__
    ```php
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
    ```  

### Provider Commands
--------------------
* __List Provider:__
    ```shell
    php artisan encryption:list
    ```
* __Create Provider:__
    ```shell
    php artisan encryption:create
    ```
* __Edit Provider:__
    ```shell
    php artisan encryption:edit
    ```
* __List Provider:__
    ```shell
    php artisan encryption:remove
    ```
* __List Provider:__
    ```shell
    php artisan encryption:key:generate
    ```
  
### Usage
--------------------
```php
<?php
$data = [1,2,3];

// Data Encrypt-Decrypt
$encrypt = app('encryption')->encrypt($data);
$decrypt = app('encryption')->decrypt($encrypt)->data();

// Custom Encryption Provider
$decrypt = app('encryption')->decrypt(
    new CryptedData(EncryptionProvider $provider, $cryptedData);
);
```
