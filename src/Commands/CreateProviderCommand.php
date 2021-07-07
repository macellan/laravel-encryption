<?php

namespace Macellan\LaravelEncryption\Commands;

use Illuminate\Console\Command;
use Macellan\LaravelEncryption\Adapters\AdapterInterface;
use Macellan\LaravelEncryption\Adapters\AdapterParamGeneratorInterface;
use Macellan\LaravelEncryption\Adapters\AdapterValidatorInterface;
use Macellan\LaravelEncryption\Models\EncryptionProvider;

/**
 * Create Provider Command
 *
 * @author Ramazan APAYDIN <ramazan.apaydin@macellan.net>
 */
class CreateProviderCommand extends Command
{
    protected $signature = 'encryption:create';

    protected $description = 'Create Encryption Provider';

    public function handle(): void
    {
        // Check Encryption Key
        $currentKey = config('encryption.encryption_key');
        if (! $currentKey && config('encryption.options_encrypt')) {
            $this->error('Encryption key (ENCYRYPTION_KEY) not found! Generate key with "php artisan encryption:key:generate"');
            return;
        }

        // Create Provider Data
        $data = [
            'title' => $this->getOutput()->ask('Provider Name', null, static function ($value) {
                if (! $value) {
                    throw new \RuntimeException('Please fill this field!');
                }
                return $value;
            }),
            'enabled' => $this->confirm('Enabled (Other Providers Will Be Disabled)', 1),
            'adapter' => $adapter = $this->choice('Encryption Adapter', config('encryption.adapters'), 0),
            'options' => self::adapterConfigurator($this, $adapter)
        ];

        // Create Model
        EncryptionProvider::create($data);
    }

    public static function adapterConfigurator(Command $command, string $adaptorClass, array $currents = []): array
    {
        $refClass = new \ReflectionClass($adaptorClass);

        // Check Interface
        if (! in_array(AdapterInterface::class, $refClass->getInterfaceNames())) {
            throw new \RuntimeException('Adapters must implement the '.AdapterInterface::class.' interface.');
        }

        // Setup Adapter Contructor Configuration
        $options = [];
        foreach ($refClass->getConstructor()->getParameters() as $parameter) {
            $default = $currents[$parameter->getName()] ?? ($parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            $question = $refClass->getShortName().'::'.$parameter->getName();
            $validator = function ($value) use ($parameter) {
                if ((! $parameter->isDefaultValueAvailable() && ! $parameter->isOptional())
                    && (! $parameter->allowsNull() && ! $value)) {
                    throw new \RuntimeException('Please fill this field!');
                }

                return $value;
            };

            $options[$parameter->getName()] = $command->getOutput()->ask($question, $default, $validator);
        }

        // Generate Adapter Default Configuration
        if (in_array(AdapterParamGeneratorInterface::class, $refClass->getInterfaceNames())) {
            if (! $options = $adaptorClass::generate($options)) {
                throw new \RuntimeException('Adapter settings could not be loaded.');
            }
        }

        // Validate Adapter Configuration
        if (in_array(AdapterValidatorInterface::class, $refClass->getInterfaceNames())) {
            if (! $adaptorClass::validate($options)) {
                throw new \RuntimeException('Adapter settings could not be verified.');
            }
        }

        return $options;
    }
}
