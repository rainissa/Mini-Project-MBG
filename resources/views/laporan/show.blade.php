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
</div>
</body>
</html>