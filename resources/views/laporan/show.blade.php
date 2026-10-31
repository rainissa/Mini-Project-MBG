<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Laporan - Pelaporan MBG</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 16px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; max-width: 640px; margin: 0 auto; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        dl { margin: 0; }
        dt { font-size: 13px; color: #6b7280; margin-top: 12px; }
        dd { margin: 2px 0 0; }
        .sukses { color: green; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Detail Laporan #{{ $laporan->id }}</h2>
    <p><a href="{{ route('laporan.index') }}">&larr; Daftar laporan</a></p>

    @if (session('sukses'))
        <p class="sukses">{{ session('sukses') }}</p>
    @endif

    <dl>
        <dt>Judul</dt><dd>{{ $laporan->judul }}</dd>
        <dt>Status</dt><dd>{{ $laporan->status }}</dd>
        <dt>Sekolah</dt><dd>{{ $laporan->distribusi->sekolah->nama }}</dd>
        <dt>Distribusi</dt>
        <dd>{{ $laporan->distribusi->tanggal_distribusi->format('d-m-Y') }} - {{ $laporan->distribusi->menu }}</dd>
        <dt>Waktu kejadian</dt><dd>{{ $laporan->waktu_kejadian->format('d-m-Y H:i') }}</dd>
        <dt>Perkiraan jumlah siswa yang mengalami keluhan</dt><dd>{{ $laporan->jumlah_terdampak }}</dd>
        <dt>Deskripsi</dt><dd>{!! nl2br(e($laporan->deskripsi)) !!}</dd>
        <dt>Bukti</dt>
        <dd>
            @if ($laporan->bukti)
                <a href="{{ route('laporan.bukti', $laporan) }}" target="_blank">Lihat bukti</a>
            @else
                Tidak ada bukti.
            @endif
        </dd>
        <dt>Catatan pemantau</dt><dd>{{ $laporan->catatan_pemantau ?? '-' }}</dd>

        @if (auth()->user()->peran !== 'pelapor')
            <dt>Pelapor</dt><dd>{{ $laporan->pelapor->nama }}</dd>
        @endif
    </dl>

    @can('update', $laporan)
        <p style="margin-top: 20px;"><a href="{{ route('laporan.edit', $laporan) }}">Edit laporan</a></p>
    @endcan
    @if ($errors->any())
        <p style="color:#b91c1c;">{{ $errors->first() }}</p>
    @endif

    @php $peran = auth()->user()->peran; @endphp

    {{-- Pelapor: kirim ulang --}}
    @if ($peran === 'pelapor' && $laporan->status === 'Perlu Dilengkapi')
        <form method="POST" action="{{ route('laporan.kirimUlang', $laporan) }}" style="margin-top:16px;">
            @csrf
            <button type="submit">Kirim ulang laporan</button>
        </form>
    @endif

    {{-- Pemantau --}}
    @if ($peran === 'pemantau')
        @if ($laporan->status === 'Dikirim')
            <form method="POST" action="{{ route('laporan.tinjau', $laporan) }}" style="margin-top:16px;">
                @csrf
                <label>Catatan (opsional)<br>
                    <textarea name="catatan" rows="2" style="width:100%"></textarea>
                </label>
                <button type="submit">Tandai sudah ditinjau</button>
            </form>
        @endif

        @if (in_array($laporan->status, ['Dikirim', 'Ditinjau']))
            <form method="POST" action="{{ route('laporan.mintaLengkapi', $laporan) }}" style="margin-top:16px;">
                @csrf
                <label>Catatan untuk pelapor (wajib)<br>
                    <textarea name="catatan" rows="2" style="width:100%" required></textarea>
                </label>
                <button type="submit">Minta dilengkapi</button>
            </form>
        @endif

        @if ($laporan->status === 'Ditinjau')
            <form method="POST" action="{{ route('laporan.tandaiDiteruskan', $laporan) }}" style="margin-top:16px;">
                @csrf
                <label>Catatan (opsional)<br>
                    <textarea name="catatan" rows="2" style="width:100%"></textarea>
                </label>
                <button type="submit">Tandai untuk diteruskan</button>
            </form>
        @endif
    @endif

    {{-- Koordinator --}}
    @if ($peran === 'koordinator')
        @if ($laporan->status === 'Ditandai untuk Diteruskan')
            <form method="POST" action="{{ route('laporan.catatPenerusan', $laporan) }}" style="margin-top:16px;">
                @csrf
                <h4>Catat penerusan informasi</h4>
                <label>Jenis penerima<br>
                    <select name="jenis_laporan" required>
                        <option value="sekolah">Sekolah</option>
                        <option value="sppg">SPPG</option>
                        <option value="fasilitas_kesehatan">Fasilitas kesehatan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </label><br>
                <label>Nama penerima<br>
                    <input type="text" name="nama_penerima" required style="width:100%">
                </label><br>
                <label>Waktu diteruskan<br>
                    <input type="datetime-local" name="waktu_diteruskan" required>
                </label><br>
                <label>Catatan (opsional)<br>
                    <textarea name="catatan_penerusan" rows="2" style="width:100%"></textarea>
                </label>
                <button type="submit">Simpan penerusan</button>
            </form>
        @endif

        @if (in_array($laporan->status, ['Ditinjau', 'Telah Diteruskan']))
            <form method="POST" action="{{ route('laporan.selesai', $laporan) }}" style="margin-top:16px;">
                @csrf
                <button type="submit">Tutup laporan (Selesai)</button>
            </form>
        @endif
    @endif

    {{-- Riwayat penerusan --}}
    @if ($laporan->penerusan->isNotEmpty() && $peran !== 'pelapor')
        <h4 style="margin-top:20px;">Riwayat penerusan</h4>
        <ul>
            @foreach ($laporan->penerusan as $p)
                <li>{{ $p->waktu_diteruskan->format('d-m-Y H:i') }} - {{ $p->nama_penerima }} ({{ $p->jenis_laporan }})</li>
            @endforeach
        </ul>
    @endif
</div>
</body>
</html>