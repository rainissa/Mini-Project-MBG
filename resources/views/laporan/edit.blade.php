<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Laporan - Pelaporan MBG</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 16px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; width: 100%; max-width: 560px; margin: 0 auto; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        label { display: block; margin-top: 14px; font-size: 14px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 4px; font-size: 14px; }
        textarea { resize: vertical; }
        button { margin-top: 18px; padding: 10px 16px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        .info { background: #f3f4f6; padding: 8px; border-radius: 4px; font-size: 14px; }
        .catatan { background: #fef3c7; padding: 8px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Edit Laporan #{{ $laporan->id }}</h2>

    <div class="info">
        {{ $laporan->distribusi->sekolah->nama }} &middot;
        {{ $laporan->distribusi->tanggal_distribusi->format('d-m-Y') }} - {{ $laporan->distribusi->menu }}
        <br><small>Sekolah dan distribusi tidak dapat diubah.</small>
    </div>

    @if ($laporan->status === 'Perlu Dilengkapi' && $laporan->catatan_pemantau)
        <p class="catatan"><strong>Catatan pemantau:</strong> {{ $laporan->catatan_pemantau }}</p>
    @endif

    <form method="POST" action="{{ route('laporan.update', $laporan) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Judul
            <input type="text" name="judul" value="{{ old('judul', $laporan->judul) }}" maxlength="150" required>
        </label>
        @error('judul') <div class="error">{{ $message }}</div> @enderror

        <label>Deskripsi kejadian
            <textarea name="deskripsi" rows="4" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
        </label>
        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror

        <label>Waktu kejadian
            <input type="datetime-local" name="waktu_kejadian"
                   value="{{ old('waktu_kejadian', $laporan->waktu_kejadian->format('Y-m-d\TH:i')) }}" required>
        </label>
        @error('waktu_kejadian') <div class="error">{{ $message }}</div> @enderror

        <label>Perkiraan jumlah siswa yang mengalami keluhan
            <input type="number" name="jumlah_terdampak" value="{{ old('jumlah_terdampak', $laporan->jumlah_terdampak) }}" min="1" required>
        </label>
        @error('jumlah_terdampak') <div class="error">{{ $message }}</div> @enderror

        <label>Ganti bukti (opsional, JPG/PNG/PDF, maks. 2 MB)
            <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf">
        </label>
        @if ($laporan->bukti)
            <small>Bukti saat ini sudah ada. Pilih file baru hanya jika ingin menggantinya.</small>
        @endif
        @error('bukti') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('laporan.show', $laporan) }}">Batal</a>
    </form>
</div>
</body>
</html>