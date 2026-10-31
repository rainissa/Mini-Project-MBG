<?php

namespace App\Http\Requests;

use App\Models\DistribusiMakanan;
use App\Models\Laporan;
use Illuminate\Foundation\Http\FormRequest;

class SimpanLaporanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->peran === 'pelapor';
    }

    public function rules(): array
    {
        return [
            'sekolah_id'=> ['required', 'exists:sekolah,id'],
            'distribusi_id'=> ['required', 'exists:distribusi_makanan,id'],
            'judul'=> ['required', 'string', 'max:150'],
            'deskripsi'=> ['required', 'string', 'min:10'],
            'waktu_kejadian'=> ['required', 'date', 'before_or_equal:now'],
            'jumlah_terdampak'=> ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }
    public function after(): array {
        return [
            function ($validator) {
                if ($validator->errors()->isNotEmpty()){
                    return;
                }
                $distribusi = DistribusiMakanan::find($this->distribusi_id);
                // BR-02: distribusi harus milik sekolah yang dipilih
                if ($distribusi->sekolah_id != $this->sekolah_id) {
                    $validator->errors()->add('distribusi_id', 'Distribusi tidak sesuai dengan sekolah yang dipilih.');
                    return;
                }
                // Waktu kejadian tidak boleh sebelum tanggal distribusi
                if ($this->date('waktu_kejadian')->startOfDay()->lt($distribusi->tanggal_distribusi)) {
                    $validator->errors()->add('waktu_kejadian', 'Waktu kejadian tidak boleh sebelum tanggal distribusi.');
                    return;
                }
                // BR-05: tidak boleh ada laporan aktif ganda untuk distribusi yang sama
                $ganda = Laporan::where('pelapor_id', $this->user()->id)
                    ->where('distribusi_id', $this->distribusi_id)
                    ->where('status', '!=', 'Selesai')
                    ->exists();
                if ($ganda) {
                    $validator->errors()->add('distribusi_id', 'Anda sudah memiliki laporan yang belum selesai untuk distribusi ini.');
                }
            },
        ];
    }
}
