<?php

namespace Macellan\LaravelEncryption\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Macellan\LaravelEncryption\Models\EncryptionProvider;
use Symfony\Component\Console\Helper\Table;

/**
 * List All Provider
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class ListProviderCommand extends Command
{
    protected $signature = 'encryption:list';

    protected $description = 'List Encryption Providers';

    public function handle(): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['Id', 'Title', 'Enabled', 'Adapter', 'Options', 'Options Crypted'])->setStyle('default');

        // Append Data
        $providers = EncryptionProvider::all(['id', 'title', 'enabled', 'adapter', 'options', 'options_crypted']);
        foreach ($providers as $provider) {
            try {
                $options = json_encode($provider->options, JSON_THROW_ON_ERROR);
            } catch (DecryptException $exception) {
                $options = 'Error: Could not load adapter settings, encryption key (ENCRYPTION_KEY) is incorrect.';
            }

            $table->addRow(
                [$provider->id, $provider->title, $provider->enabled, $provider->adapter, $options, $provider->options_crypted]
            );
        }

        // View
        $table->render();
    }
}
