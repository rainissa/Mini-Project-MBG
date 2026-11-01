<?php

namespace Database\Seeders;

use App\Models\DistribusiMakanan;
use Illuminate\Database\Seeder;

class DistribusiMakananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [1, '2026-10-19', 'Nasi, ayam goreng, sayur bening'],
            [1, '2026-10-26', 'Nasi, telur balado, tumis kangkung'],
            [2, '2026-10-20', 'Nasi, ikan goreng, sayur sop'],
            [2, '2026-10-27', 'Nasi, tempe orek, capcay'],
            [3, '2026-10-21', 'Nasi, daging semur, tumis buncis'],
            [3, '2026-10-28', 'Nasi, ayam kecap, sayur lodeh'],
            [1, '2026-10-29', 'Nasi, soto ayam, kerupuk'],
            [2, '2026-10-30', 'Nasi, pepes ikan, lalapan'],
            [3, '2026-10-30', 'Nasi, rendang, sayur nangka'],
        ];

        foreach ($data as [$sekolahId, $tanggal, $menu]) {
            DistribusiMakanan::create([
                'sekolah_id'         => $sekolahId,
                'tanggal_distribusi' => $tanggal,
                'menu'               => $menu,
            ]);
        }
    }
}
