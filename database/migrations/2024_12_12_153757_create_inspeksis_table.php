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
        Schema::create('inspeksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metode_id')->constrained('metode')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->foreignId('hama_id')->constrained('hama')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('pegawai');
            $table->integer('jumlah');
            $table->timestamps();
        });

        Schema::create('inspeksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspeksi_id')->constrained('inspeksi')->onDelete('cascade');
            $table->foreignId('tindakan_id')->constrained('tindakan')->onDelete('cascade');
            $table->boolean('check')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeksi');
        Schema::dropIfExists('inspeksi_detail');
    }
};
