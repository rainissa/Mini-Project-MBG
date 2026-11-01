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
        .filter { display: flex; flex-wrap: wrap; gap: 8px; align-items: flex-end; margin-top: 12px; }
        .filter label { font-size: 13px; display: flex; flex-direction: column; }
        .filter input, .filter select { padding: 6px; font-size: 14px; }
        .filter button { padding: 8px 14px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Daftar Laporan</h2>
    <p><a href="{{ route('beranda') }}">&larr; Beranda</a></p>

    @if (session('sukses'))
        <p class="sukses">{{ session('sukses') }}</p>
    @endif
    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form method="GET" action="{{ route('laporan.index') }}" class="filter">
        <label>Kata kunci
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul atau deskripsi">
        </label>

        <label>Status
            <select name="status">
                <option value="">Semua</option>
                @foreach ($daftarStatus as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </label>

        @if (auth()->user()->peran !== 'pelapor')
            <label>Sekolah
                <select name="sekolah">
                    <option value="">Semua</option>
                    @foreach ($daftarSekolah as $sk)
                        <option value="{{ $sk->id }}" @selected(request('sekolah') == $sk->id)>{{ $sk->nama }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        <label>Dari tanggal
            <input type="date" name="dari" value="{{ request('dari') }}">
        </label>

        <label>Sampai tanggal
            <input type="date" name="sampai" value="{{ request('sampai') }}">
        </label>

        <button type="submit">Cari</button>
        <a href="{{ route('laporan.index') }}">Reset</a>
    </form>
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
        @if ($daftarLaporan->hasPages())
        <div style="margin-top: 12px;">
            @if ($daftarLaporan->onFirstPage())
                <span>&laquo; Sebelumnya</span>
            @else
                <a href="{{ $daftarLaporan->previousPageUrl() }}">&laquo; Sebelumnya</a>
            @endif

            &nbsp; Halaman {{ $daftarLaporan->currentPage() }} dari {{ $daftarLaporan->lastPage() }} &nbsp;

            @if ($daftarLaporan->hasMorePages())
                <a href="{{ $daftarLaporan->nextPageUrl() }}">Berikutnya &raquo;</a>
            @else
                <span>Berikutnya &raquo;</span>
            @endif
        </div>
    @endif
    </div>
</div>
</body>
</html>