<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EncryptionProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('encryption_providers', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('enabled');
            $table->string('adapter');
            $table->text('options')->nullable();
            $table->boolean('options_crypted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('encryption_providers');
    }
}
