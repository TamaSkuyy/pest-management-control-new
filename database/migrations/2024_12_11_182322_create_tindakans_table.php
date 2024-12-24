<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tindakan', function (Blueprint $table) {
            $table->id();
            $table->string('tindakan_kode');
            $table->string('tindakan_nama');
            $table->foreignId('metode_id')->nullable()->constrained('metode')->onDelete('cascade');
            $table->string('hama_id')->nullable()->constrained('metode')->onDelete('cascade');
            // $table->foreignId('hama_id')->constrained('hama')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan');
    }
};
