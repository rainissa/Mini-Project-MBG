<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UbahLaporanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul'            => ['required', 'string', 'max:150'],
            'deskripsi'        => ['required', 'string', 'min:10'],
            'waktu_kejadian'   => ['required', 'date', 'before_or_equal:now'],
            'jumlah_terdampak' => ['required', 'integer', 'min:1', 'max:1000'],
            'bukti'            => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
    public function messages(): array {
        return [
            'waktu_kejadian.before_or_equal' => 'Waktu kejadian tidak boleh di masa depan.',
            'bukti.mimes'    => 'File bukti harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti.max'      => 'Ukuran file bukti maksimal 2 MB.',
            'bukti.uploaded' => 'File gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.',
        ];
    }
    public function after(): array {
        return [
            function ($validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
                $laporan = $this->route('laporan');
                if ($this->date('waktu_kejadian')->startOfDay()->lt($laporan->distribusi->tanggal_distribusi)) {
                    $validator->errors()->add('waktu_kejadian', 'Waktu kejadian tidak boleh sebelum tanggal distribusi.');
                }
            },
        ];
    }
}
