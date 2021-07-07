<?php

namespace Macellan\LaravelEncryption\Commands;

use Illuminate\Console\Command;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Provider Remove Command
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class RemoveProviderCommand extends Command
{
    protected $signature = 'encryption:remove';

    protected $description = 'Remove Encryption Provider';

    public function handle(): void
    {
        // Find and Remove
        $provider = EncryptionProvider::find(
            $this->getOutput()->ask('Provider ID:', false, static function ($value) {
                if (! EncryptionProvider::find($value)) {
                    throw new \RuntimeException('Encryption provider not found.');
                }

                return $value;
            })
        );

        // Success
        $provider->delete();
        $this->alert('Provider deleted.');
    }
}
