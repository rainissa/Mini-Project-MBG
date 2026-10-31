<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanLaporanRequest;
use App\Models\DistribusiMakanan;
use App\Models\Laporan;
use App\Models\Sekolah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UbahLaporanRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('view', $laporan); 
        abort_unless($laporan->bukti && Storage::exists($laporan->bukti), 404);
        return Storage::response($laporan->bukti);
    }
    public function index() {
        Gate::authorize('viewAny', Laporan::class);
        $user = auth()->user();
        $query = Laporan::with(['pelapor', 'distribusi.sekolah'])->latest();
        // Pelapor hanya melihat laporan miliknya
        if ($user->peran === 'pelapor') {
            $query->where('pelapor_id', $user->id);
        }
        return view('laporan.index', [
            'daftarLaporan' => $query->paginate(10),
        ]);
    }
    public function show(Laporan $laporan) {
        Gate::authorize('view', $laporan); 
        $laporan->load(['pelapor', 'distribusi.sekolah']);
        return view('laporan.show', ['laporan' => $laporan]);
    }
    public function edit(Laporan $laporan) {
        Gate::authorize('update', $laporan);
        $laporan->load('distribusi.sekolah');
        return view('laporan.edit', ['laporan' => $laporan]);
    }
    public function update(UbahLaporanRequest $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('update', $laporan);
        $data = $request->safe()->only(['judul', 'deskripsi', 'waktu_kejadian', 'jumlah_terdampak']);
        if ($request->hasFile('bukti')) {
            // Hapus file lama agar tidak menumpuk
            if ($laporan->bukti) {
                Storage::delete($laporan->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('bukti');
        }
        $laporan->update($data);
        return redirect()->route('laporan.show', $laporan)->with('sukses', 'Laporan berhasil diperbarui.');
    }
}
