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
        $this->loadMigrations();
        $this->loadCommands();
    }

    private function loadConfig(): void
    {
        $this->publishes([__DIR__.'/../../config/encryption.php' => config_path('encryption.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/../../config/encryption.php', 'encryption');
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
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
