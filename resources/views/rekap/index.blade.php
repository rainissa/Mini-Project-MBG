<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Laporan - Pelaporan MBG</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 16px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; max-width: 960px; margin: 0 auto; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        .filter { display: flex; flex-wrap: wrap; gap: 8px; align-items: flex-end; margin: 12px 0; }
        .filter label { font-size: 13px; display: flex; flex-direction: column; }
        .filter input, .filter select { padding: 6px; font-size: 14px; }
        .filter button { padding: 8px 14px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .ringkasan { display: flex; flex-wrap: wrap; gap: 12px; margin: 16px 0; }
        .angka { flex: 1 1 140px; background: #f3f4f6; border-radius: 6px; padding: 12px; }
        .angka strong { display: block; font-size: 26px; }
        .angka span { font-size: 13px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        .tabel-gulir { overflow-x: auto; }
        .error { color: #b91c1c; font-size: 13px; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Rekap Laporan</h2>
    <p><a href="{{ route('beranda') }}">&larr; Beranda</a></p>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form method="GET" action="{{ route('rekap.index') }}" class="filter">
        <label>Sekolah
            <select name="sekolah">
                <option value="">Semua</option>
                @foreach ($daftarSekolah as $s)
                    <option value="{{ $s->id }}" @selected(request('sekolah') == $s->id)>{{ $s->nama }}</option>
                @endforeach
            </select>
        </label>
        <label>Dari tanggal
            <input type="date" name="dari" value="{{ request('dari') }}">
        </label>
        <label>Sampai tanggal
            <input type="date" name="sampai" value="{{ request('sampai') }}">
        </label>
        <button type="submit">Terapkan</button>
        <a href="{{ route('rekap.index') }}">Reset</a>
    </form>

    <div class="ringkasan">
        <div class="angka"><strong>{{ $ringkasan['total'] }}</strong><span>Total laporan</span></div>
        <div class="angka"><strong>{{ $ringkasan['diproses'] }}</strong><span>Masih diproses</span></div>
        <div class="angka"><strong>{{ $ringkasan['diteruskan'] }}</strong><span>Menunggu diteruskan</span></div>
        <div class="angka"><strong>{{ $ringkasan['selesai'] }}</strong><span>Selesai</span></div>
    </div>

    <h3>Rekap per sekolah</h3>
    <div class="tabel-gulir">
        <table>
            <thead>
                <tr><th>Sekolah</th><th>Total</th><th>Masih diproses</th><th>Selesai</th></tr>
            </thead>
            <tbody>
                @forelse ($tabel as $baris)
                    <tr>
                        <td>{{ $baris->nama }}</td>
                        <td>{{ $baris->total }}</td>
                        <td>{{ $baris->diproses }}</td>
                        <td>{{ $baris->selesai }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Tidak ada data untuk filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>