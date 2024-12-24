<?php

namespace Database\Seeders;

use App\Models\Tindakan;
use Illuminate\Database\Seeder;

class TindakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tindakan::create([
            'tindakan_kode' => 'TK001',
            'tindakan_nama' => 'Penggunaan Pestisida',
            'hama_id' => '1',
        ]);

        Tindakan::create([
            'tindakan_kode' => 'TK002',
            'tindakan_nama' => 'Penggunaan Trichogramma',
            'hama_id' => '2',
        ]);

        Tindakan::create([
            'tindakan_kode' => 'TK003',
            'tindakan_nama' => 'Penggunaan Parasitoid',
            'hama_id' => '3',
        ]);

    }
}
