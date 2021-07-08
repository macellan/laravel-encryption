<?php

namespace Macellan\LaravelEncryption\Providers;

use Illuminate\Support\ServiceProvider;
use Macellan\LaravelEncryption\Commands\CreateProviderCommand;
use Macellan\LaravelEncryption\Commands\EditProviderCommand;
use Macellan\LaravelEncryption\Commands\GenerateKeyCommand;
use Macellan\LaravelEncryption\Commands\ListProviderCommand;
use Macellan\LaravelEncryption\Commands\RemoveProviderCommand;
use Macellan\LaravelEncryption\Manager\EncryptionManager;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Encryption Register to Laravel Service
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class EncryptionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadConfig();
        $this->loadPublishing();
        $this->loadCommands();
    }

    private function loadConfig(): void
    {
        $this->publishes([__DIR__.'/../../config/encryption.php' => config_path('encryption.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/../../config/encryption.php', 'encryption');
    }

    private function loadPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../../config/encryption.php' => config_path('encryption.php'),
        ], 'encryption');

        $this->publishes([
            __DIR__.'/../../database/migrations/encryption_provider.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_encryption_provider.php'),
        ], 'encryption');
    }

    private function loadCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ListProviderCommand::class,
                CreateProviderCommand::class,
                EditProviderCommand::class,
                RemoveProviderCommand::class,
                GenerateKeyCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton('encryption', function ($app) {
            $provider = EncryptionProvider::getActiveProvider();
            if ($provider) {
                return new EncryptionManager($provider);
            }

            return null;
        });
    }
}
