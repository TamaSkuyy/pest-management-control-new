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
            $table->string('bahan_aktif')->nullable();
            $table->string('jumlah_bait')->nullable();
            $table->string('kondisi_umpan_utuh_bait')->nullable();
            $table->string('kondisi_umpan_kurang_bait')->nullable();
            $table->string('kondisi_umpan_rusak_bait')->nullable();
            $table->string('tindakan_ganti_bait')->nullable();
            $table->string('tindakan_tambah_bait')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_detail', function (Blueprint $table) {
            $table->dropColumn(['bahan_aktif', 'jumlah_bait', 'kondisi_umpan_utuh_bait', 'kondisi_umpan_kurang_bait', 'kondisi_umpan_rusak_bait', 'tindakan_ganti_bait', 'tindakan_tambah_bait']);
        });
    }

};
