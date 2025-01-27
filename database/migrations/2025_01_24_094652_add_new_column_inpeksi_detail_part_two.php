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
        Schema::table('inspeksi_detail', function (Blueprint $table) {
            $table->enum('kondisi_rbs', ['OK', 'RUSAK', 'HILANG'])->nullable();
            $table->enum('tindakan_rbs', ['GANTI', 'CLEANING'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_detail', function (Blueprint $table) {
            $table->dropColumn(['kondisi_rbs', 'tindakan_rbs']);
        });
    }
};
