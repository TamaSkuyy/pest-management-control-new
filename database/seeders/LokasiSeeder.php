<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lokasi::create([
            'lokasi_kode' => 'PMC001',
            'lokasi_nama' => 'Warehouse A',
            'lokasi_jenis' => '1',
        ]);

        Lokasi::create([
            'lokasi_kode' => 'PMC002',
            'lokasi_nama' => 'Greenhouse 1',
            'lokasi_jenis' => '2',
        ]);

        Lokasi::create([
            'lokasi_kode' => 'PMC003',
            'lokasi_nama' => 'Food Processing Plant',
            'lokasi_jenis' => '1',
        ]);

    }
}
