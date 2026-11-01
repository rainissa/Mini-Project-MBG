<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'sekolah' => ['nullable', 'integer', 'exists:sekolah,id'],
            'dari'    => ['nullable', 'date'],
            'sampai'  => ['nullable', 'date', 'after_or_equal:dari'],
        ]);
        // Dasar query: laporan + distribusi + sekolah, lengkap dengan filter
        $dasar = function () use ($filter) {
            $q = DB::table('laporan')
                ->join('distribusi_makanan', 'distribusi_makanan.id', '=', 'laporan.distribusi_id')
                ->join('sekolah', 'sekolah.id', '=', 'distribusi_makanan.sekolah_id');
            if (! empty($filter['sekolah'])) {
                $q->where('sekolah.id', $filter['sekolah']);
            }
            if (! empty($filter['dari'])) {
                $q->whereDate('laporan.waktu_kejadian', '>=', $filter['dari']);
            }
            if (! empty($filter['sampai'])) {
                $q->whereDate('laporan.waktu_kejadian', '<=', $filter['sampai']);
            }
            return $q;
        };
        $ringkasan = [
            'total'    => $dasar()->count(),
            'diproses' => $dasar()->where('laporan.status', '!=', 'Selesai')->count(),
            'diteruskan' => $dasar()->where('laporan.status', 'Ditandai untuk Diteruskan')->count(),
            'selesai'  => $dasar()->where('laporan.status', 'Selesai')->count(),
        ];
        $tabel = $dasar()
            ->select(
                'sekolah.nama',
                DB::raw('COUNT(laporan.id) as total'),
                DB::raw("SUM(CASE WHEN laporan.status <> 'Selesai' THEN 1 ELSE 0 END) as diproses"),
                DB::raw("SUM(CASE WHEN laporan.status = 'Selesai' THEN 1 ELSE 0 END) as selesai")
            )
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderByDesc('total')
            ->get();
        return view('rekap.index', [
            'ringkasan'     => $ringkasan,
            'tabel'         => $tabel,
            'daftarSekolah' => Sekolah::orderBy('nama')->get(),
        ]);
    }
}
