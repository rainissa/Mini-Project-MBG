<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanLaporanRequest;
use App\Models\DistribusiMakanan;
use App\Models\Laporan;
use App\Models\Sekolah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function create() {
        return view('laporan.create', [
            'daftarSekolah' => Sekolah::orderBy('nama')->get(),
        ]);
    }
    public function store(SimpanLaporanRequest $request): RedirectResponse {
        $path = null;
        if ($request->hasFile('bukti')) {
            $path = $request->file('bukti')->store('bukti');
        }
        Laporan::create([
            'pelapor_id'       => $request->user()->id, // dari akun login, bukan dari form
            'distribusi_id'    => $request->distribusi_id,
            'judul'            => $request->judul,
            'deskripsi'        => $request->deskripsi,
            'waktu_kejadian'   => $request->waktu_kejadian,
            'jumlah_terdampak' => $request->jumlah_terdampak,
            'status'           => 'Dikirim',
            'bukti'            => $path,
        ]);
        return redirect()->route('beranda')->with('sukses', 'Laporan berhasil dikirim.');
    }
    public function distribusiPerSekolah(Sekolah $sekolah): JsonResponse {
        $data = DistribusiMakanan::where('sekolah_id', $sekolah->id)
            ->orderByDesc('tanggal_distribusi')
            ->get(['id', 'tanggal_distribusi', 'menu'])
            ->map(fn ($d) => [
                'id'    => $d->id,
                'label' => $d->tanggal_distribusi->format('d-m-Y') . ' - ' . $d->menu,
            ]);
        return response()->json($data);
    }
    public function bukti(Laporan $laporan) {
        $user = auth()->user();
        $boleh = $user->peran !== 'pelapor' || $laporan->pelapor_id === $user->id;
        abort_unless($boleh, 403);
        abort_unless($laporan->bukti && Storage::exists($laporan->bukti), 404);
        return Storage::response($laporan->bukti);
    }
}
