<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Laporan - Pelaporan MBG</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 16px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; max-width: 960px; margin: 0 auto; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 14px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .tabel-gulir { overflow-x: auto; }
        .sukses { color: green; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Daftar Laporan</h2>
    <p><a href="{{ route('beranda') }}">&larr; Beranda</a></p>

    @if (session('sukses'))
        <p class="sukses">{{ session('sukses') }}</p>
    @endif

    <div class="tabel-gulir">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Sekolah</th>
                    <th>Tanggal distribusi</th>
                    @if (auth()->user()->peran !== 'pelapor')
                        <th>Pelapor</th>
                    @endif
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarLaporan as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td>{{ $l->judul }}</td>
                        <td>{{ $l->distribusi->sekolah->nama }}</td>
                        <td>{{ $l->distribusi->tanggal_distribusi->format('d-m-Y') }}</td>
                        @if (auth()->user()->peran !== 'pelapor')
                            <td>{{ $l->pelapor->nama }}</td>
                        @endif
                        <td>{{ $l->status }}</td>
                        <td><a href="{{ route('laporan.show', $l) }}">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 12px;">
        {{ $daftarLaporan->links() }}
    </div>
</div>
</body>
</html>