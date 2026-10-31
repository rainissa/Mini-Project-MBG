<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Laporan - Pelaporan MBG</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: sans-serif; background: #f3f4f6; padding: 24px; }
        .kartu { background: #fff; padding: 24px; border-radius: 8px; max-width: 560px; margin: auto; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        label { display: block; margin-top: 14px; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 18px; padding: 10px 16px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
<div class="kartu">
    <h2>Buat Laporan</h2>
    <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Sekolah
            <select name="sekolah_id" id="sekolah" required>
                <option value="">-- Pilih sekolah --</option>
                @foreach ($daftarSekolah as $s)
                    <option value="{{ $s->id }}" @selected(old('sekolah_id') == $s->id)>{{ $s->nama }}</option>
                @endforeach
            </select>
        </label>
        @error('sekolah_id') <div class="error">{{ $message }}</div> @enderror

        <label>Distribusi makanan
            <select name="distribusi_id" id="distribusi" required>
                <option value="">-- Pilih sekolah terlebih dahulu --</option>
            </select>
        </label>
        @error('distribusi_id') <div class="error">{{ $message }}</div> @enderror

        <label>Judul
            <input type="text" name="judul" value="{{ old('judul') }}" maxlength="150" required>
        </label>
        @error('judul') <div class="error">{{ $message }}</div> @enderror

        <label>Deskripsi kejadian
            <textarea name="deskripsi" rows="4" required>{{ old('deskripsi') }}</textarea>
        </label>
        @error('deskripsi') <div class="error">{{ $message }}</div> @enderror

        <label>Waktu kejadian
            <input type="datetime-local" name="waktu_kejadian" value="{{ old('waktu_kejadian') }}" required>
        </label>
        @error('waktu_kejadian') <div class="error">{{ $message }}</div> @enderror

        <label>Perkiraan jumlah siswa yang mengalami keluhan
            <input type="number" name="jumlah_terdampak" value="{{ old('jumlah_terdampak') }}" min="1" required>
        </label>
        @error('jumlah_terdampak') <div class="error">{{ $message }}</div> @enderror

        <label>Bukti pendukung (opsional, JPG/PNG/PDF, maks. 2 MB)
            <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf">
        </label>
        @error('bukti') <div class="error">{{ $message }}</div> @enderror
        <button type="submit">Kirim Laporan</button>
        <a href="{{ route('beranda') }}">Batal</a>
    </form>
</div>

<script>
    const pilihSekolah = document.getElementById('sekolah');
    const pilihDistribusi = document.getElementById('distribusi');
    const distribusiLama = "{{ old('distribusi_id') }}";

    async function muatDistribusi(sekolahId) {
        pilihDistribusi.innerHTML = '<option value="">-- Memuat... --</option>';

        if (!sekolahId) {
            pilihDistribusi.innerHTML = '<option value="">-- Pilih sekolah terlebih dahulu --</option>';
            return;
        }

        try {
            const respons = await fetch(`/sekolah/${sekolahId}/distribusi`, {
                headers: { 'Accept': 'application/json' },
            });
            if (!respons.ok) throw new Error('Gagal memuat');

            const data = await respons.json();
            pilihDistribusi.innerHTML = '<option value="">-- Pilih distribusi --</option>';
            data.forEach(d => {
                const opsi = document.createElement('option');
                opsi.value = d.id;
                opsi.textContent = d.label;
                if (String(d.id) === distribusiLama) opsi.selected = true;
                pilihDistribusi.appendChild(opsi);
            });
        } catch (e) {
            pilihDistribusi.innerHTML = '<option value="">-- Gagal memuat distribusi --</option>';
        }
    }

    pilihSekolah.addEventListener('change', () => muatDistribusi(pilihSekolah.value));

    // Jika form dikembalikan karena error validasi, muat ulang daftar distribusinya
    if (pilihSekolah.value) muatDistribusi(pilihSekolah.value);
</script>
</body>
</html>