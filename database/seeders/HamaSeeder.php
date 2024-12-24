<?php

namespace Database\Seeders;

use App\Models\Hama;
use Illuminate\Database\Seeder;

class HamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hama::create([
            'hama_kode' => 'PGT',
            'hama_nama' => 'Hama PGT',
            'metode_id' => '1',
        ]);

        Hama::create([
            'hama_kode' => 'FS',
            'hama_nama' => 'Hama FS',
            'metode_id' => '2',
        ]);

        Hama::create([
            'hama_kode' => 'RBT',
            'hama_nama' => 'Hama RBT',
            'metode_id' => '3',
        ]);

    }
}
