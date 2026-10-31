<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\PenerusanLaporan;
use App\Services\LaporanWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ProsesLaporanController extends Controller
{
    public function __construct(private LaporanWorkflow $workflow) {

    }
    // Pemantau: Dikirim -> Ditinjau
    public function tinjau (Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('tinjau', $laporan);
        $data = $request->validate([
            'catatan'=> ['nullable', 'string', 'max:1000'],
        ]);
        $this->workflow->pindahkan($laporan, 'Ditinjau', $request->user(), $data['catatan']?? null);
        return back()->with('sukses', 'Laporan ditandai sudah ditinjau.');
    }
    // Pemantau: minta pelapor melengkapi
    public function mintaLengkapi(Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('tinjau', $laporan);
        $data = $request->validate([
            'catatan'=> ['required', 'string', 'max:1000'],
        ]);
        $this->workflow->pindahkan($laporan, 'Perlu Dilengkapi', $request->user(), $data['catatan']);
        return back()->with('sukses', 'Permintaan melengkapi laporan dikirim ke pelapor.');
    }
    // Pemantau: tandai perlu diteruskan
    public function tandaiDiteruskan(Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('tinjau', $laporan);
        $data = $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);
        $this->workflow->pindahkan($laporan, 'Ditandai untuk Diteruskan', $request->user(), $data['catatan'] ?? null);
        return back()->with('sukses', 'Laporan ditandai untuk diteruskan kepada pihak terkait.');
    }
    // Pelapor: kirim ulang setelah diperbaiki
    public function kirimUlang(Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('view', $laporan);
        $this->workflow->pindahkan($laporan, 'Dikirim', $request->user());
        return back()->with('sukses', 'Laporan dikirim ulang.');
    }
    // Koordinator: catat penerusan (BR-04) lalu status Telah Diteruskan
    public function catatPenerusan(Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('catatPenerusan', $laporan);
        $data = $request->validate([
            'jenis_laporan'    => ['required', 'in:sekolah,sppg,fasilitas_kesehatan,lainnya'],
            'nama_penerima'     => ['required', 'string', 'max:255'],
            'catatan_penerusan' => ['nullable', 'string', 'max:1000'],
            'waktu_diteruskan'  => ['required', 'date', 'before_or_equal:now'],
        ]);
        // BR-04: penerusan hanya boleh dicatat pada laporan yang sudah ditandai pemantau
        if ($laporan->status !== 'Ditandai untuk Diteruskan') {
            throw ValidationException::withMessages([
                'status' => 'Penerusan hanya dapat dicatat pada laporan yang sudah ditandai untuk diteruskan.',
            ]);
        }
        DB::transaction(function () use ($data, $laporan, $request) {
            PenerusanLaporan::create([
                'laporan_id'        => $laporan->id,
                'jenis_laporan'    => $data['jenis_laporan'],
                'nama_penerima'     => $data['nama_penerima'],
                'catatan_penerusan' => $data['catatan_penerusan'] ?? null,
                'dicatat_oleh'      => $request->user()->id,
                'waktu_diteruskan'  => $data['waktu_diteruskan'],
            ]);
            $this->workflow->pindahkan($laporan, 'Telah Diteruskan', $request->user());
        });
        return back()->with('sukses', 'Penerusan informasi berhasil dicatat.');
    }
    // Koordinator: tutup laporan
    public function selesaikan(Request $request, Laporan $laporan): RedirectResponse {
        Gate::authorize('catatPenerusan', $laporan);
        $this->workflow->pindahkan($laporan, 'Selesai', $request->user());
        return back()->with('sukses', 'Laporan ditutup.');
    }
}
