<?php

namespace Macellan\LaravelEncryption\Commands;

use Illuminate\Foundation\Console\KeyGenerateCommand;

/**
 * Generate Encryption Key
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class GenerateKeyCommand extends KeyGenerateCommand
{
    protected $signature = 'encryption:key:generate';

    protected $description = 'Generates Encryption Key for Options Encryption';

    public function handle(): void
    {
        $key = $this->generateRandomKey();

        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $this->laravel['config']['encryption.encryption_key'] = $key;

        $this->line('<comment>'.$key.'</comment>');
        $this->info('Encryption key generated, keep this key and never change!!');
    }


    /**
     * Set the encryption key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key): bool
    {
        $currentKey = config('encryption.encryption_key', '');
        if ($currentKey) {
            if (! $this->confirm('Encryption key exists, are you sure you want to change it?', false)) {
                return false;
            }

            // Force
            if (! $this->confirm('This operation is "IRRECOVERABLE" and data encrypted with the current key is non-recoverable!', false)) {
                return false;
            }
        }

        // Set New
        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key): void
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern(),
            'ENCRYPTION_KEY='.$key,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env APP_ENCRYPTION_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern(): string
    {
        $escaped = preg_quote('='.env('ENCRYPTION_KEY', ''), '/');

        return "/^ENCRYPTION_KEY{$escaped}/m";
    }
}
