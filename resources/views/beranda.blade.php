<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Pelaporan MBG</title>
</head>
<body style="font-family: sans-serif; padding: 24px;">
    <h2>Beranda</h2>
    <p>Halo, {{ auth()->user()->nama }} (peran: {{ auth()->user()->peran }})</p>
    @if (session('sukses'))
        <p style="color: green;">{{ session('sukses') }}</p>
    @endif
    @if (auth()->user()->peran === 'pelapor')
        <p><a href="{{ route('laporan.create') }}">Buat laporan baru</a></p>
    @endif
    <p><a href="{{ route('laporan.index') }}">
        {{ auth()->user()->peran === 'pelapor' ? 'Laporan saya' : 'Daftar laporan' }}
    </a></p>
    @if (in_array(auth()->user()->peran, ['pemantau', 'koordinator']))
        <p><a href="{{ route('rekap.index') }}">Rekap laporan</a></p>
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>