<?php

namespace Macellan\LaravelEncryption\Models;

use Eloquent;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Macellan\LaravelEncryption\Adapters\AdapterInterface;
use Macellan\LaravelEncryption\Manager\OptionsEncrypter;

/**
 * @property int $id
 * @property string $title
 * @property boolean $enabled
 * @property string $adapter
 * @property string $options
 * @property boolean $options_crypted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|EncryptionProvider newModelQuery()
 * @method static Builder|EncryptionProvider newQuery()
 * @method static Builder|EncryptionProvider query()
 * @mixin Eloquent
 */
class EncryptionProvider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'enabled', 'adapter', 'options', 'options_crypted'];

    private AdapterInterface $adapterInstance;

    public static function getActiveProvider(): ?self
    {
        return self::firstWhere(['enabled' => 1]);
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapterInstance ?? $this->adapterInstance = new $this->adapter(...array_values((array)$this->options));
    }

    public function getOptionsAttribute($value): ?array
    {
        try {
            return is_array($value) ? $value : OptionsEncrypter::decrypt($value, $this->options_crypted);
        } catch (Exception $exception) {
            throw new DecryptException('Adaptor config decrypt failed.');
        }
    }

    protected static function booted()
    {
        $disableOtherProvider = static function (EncryptionProvider $provider) {
            // Disable Other Provider
            if ($provider->enabled) {
                EncryptionProvider::where('enabled', true)->update(['enabled' => false]);
            }

            // Encrypt Options
            $provider->options = OptionsEncrypter::encrypt($provider->options);
            $provider->options_crypted = config('encryption.options_encrypt', false);
        };

        self::updating($disableOtherProvider);
        self::creating($disableOtherProvider);
    }
}
