<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\DistribusiMakanan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // [pelapor_id, distribusi_id, judul, jumlah, status, catatan_pemantau]
        $data = [
            [3, 1, 'Beberapa siswa mual setelah makan siang', 8,  'Dikirim', null],
            [3, 2, 'Keluhan sakit perut usai makan',          5,  'Perlu Dilengkapi', 'Mohon cantumkan perkiraan jam keluhan muncul.'],
            [4, 3, 'Siswa mengeluh pusing',                   3,  'Ditinjau', 'Informasi lengkap, belum perlu diteruskan.'],
            [4, 4, 'Beberapa siswa muntah',                   10, 'Ditandai untuk Diteruskan', 'Ada laporan lain di distribusi yang sama.'],
            [3, 5, 'Makanan berbau tidak sedap',              2,  'Telah Diteruskan', 'Sudah ditinjau dan ditandai.'],
            [4, 6, 'Siswa diare ringan',                      4,  'Selesai', 'Sudah ditindaklanjuti.'],
            [4, 1, 'Anak saya sakit perut setelah makan',     1,  'Dikirim', null],
        ];

        foreach ($data as [$pelapor, $distribusi, $judul, $jumlah, $status, $catatan]) {
            Laporan::create([
                'pelapor_id'       => $pelapor,
                'distribusi_id'    => $distribusi,
                'judul'            => $judul,
                'deskripsi'        => 'Data demo: ' . $judul . '. Keluhan dilaporkan beberapa jam setelah makanan dibagikan.',
                'waktu_kejadian'   => DistribusiMakanan::find($distribusi)->tanggal_distribusi->copy()->setTime(14, 0),
                'jumlah_terdampak' => $jumlah,
                'status'           => $status,
                'catatan_pemantau' => $catatan,
            ]);
        }
        for ($i = 1; $i <= 6; $i++) {
            Laporan::create([
                'pelapor_id'       => 4,
                'distribusi_id'    => 3,
                'judul'            => "Keluhan tambahan nomor {$i}",
                'deskripsi'        => 'Data demo untuk menguji pagination dan filter.',
                'waktu_kejadian'   => '2026-10-20 14:00:00',
                'jumlah_terdampak' => $i,
                'status'           => $i % 2 === 0 ? 'Ditinjau' : 'Dikirim',
            ]);
        }
    }
}
