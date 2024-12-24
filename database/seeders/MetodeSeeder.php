<?php

namespace Database\Seeders;

use App\Models\Metode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // jenis 1: indoor, 2: outdoor
        Metode::create([
            'metode_kode' => 'PGT',
            'metode_nama' => 'PGT',
            'metode_jenis' => 1,
        ]);
        Metode::create([
            'metode_kode' => 'FS',
            'metode_nama' => 'FS',
            'metode_jenis' => 2,
        ]);
        Metode::create([
            'metode_kode' => 'RBT',
            'metode_nama' => 'RBT',
            'metode_jenis' => 1,
        ]);
    }
}
