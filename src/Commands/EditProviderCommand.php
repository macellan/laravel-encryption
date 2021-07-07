<?php

namespace Macellan\LaravelEncryption\Commands;

use Illuminate\Console\Command;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Edit Provider Command
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class EditProviderCommand extends Command
{
    protected $signature = 'encryption:edit';

    protected $description = 'Edit Encryption Provider';

    public function handle(): void
    {
        // Find Provider
        $provider = EncryptionProvider::find(
            $this->getOutput()->ask('Provider ID:', false, static function ($value) {
                if (! EncryptionProvider::find($value)) {
                    throw new \RuntimeException('Encryption provider not found.');
                }
                return $value;
            })
        );

        // Edit
        $data = [
            'title' => $this->getOutput()->ask('Provider Name', null, static function ($value) {
                if (! $value) {
                    throw new \RuntimeException('Please fill this field!');
                }
                return $value;
            }),
            'enabled' => $this->confirm('Enabled (Other Providers Will Be Disabled)', 1),
            'options' => CreateProviderCommand::adapterConfigurator($this, $provider->adapter, $provider->options)
        ];

        // Update
        $provider->update($data);
    }
}
