<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\MetodeSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metode', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('metode_kode');
            $table->string('metode_nama');
            $table->tinyInteger('metode_jenis')->default(1)->comment('1: indoor, 2: outdoor');
            $table->timestamps();
        });
    }

    /**
     * Indicates if the migration seeds the database.
     */
    public function seeds(): array
    {
        return [
            MetodeSeeder::class,
        ];
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode');
    }
};
