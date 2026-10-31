<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['SMP Al Kenzie', 'SDN Margahayu Raya', 'SMA 21 Bandung'] as $nama) {
            Sekolah::create(['nama' => $nama]);
        }
    }
}
