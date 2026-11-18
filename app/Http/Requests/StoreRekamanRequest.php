<?php

namespace App\Http\Requests;

use App\Models\Rekaman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRekamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'spesies_id' => ['required', 'integer', 'exists:spesies,id'],
            'lokasi_id' => ['required', 'integer', 'exists:lokasi,id'],
            'tanggal_rekam' => ['required', 'date', 'before_or_equal:today'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'durasi_detik' => ['required', 'integer', 'min:1', 'max:86400'],
            'sample_rate' => ['required', 'integer', Rule::in([8000, 16000, 22050, 44100, 48000, 96000, 192000])],
            'format' => ['required', Rule::in(Rekaman::FORMAT)],
            'kualitas' => ['required', Rule::in(Rekaman::KUALITAS)],
            'kondisi_cuaca' => ['nullable', 'string', 'max:60'],
            'catatan' => ['nullable', 'string', 'max:2000'],

            // Pilihan jamak: peneliti (multi-select) + penanggung jawab (pivot 'peran').
            'peneliti_ids' => ['required', 'array', 'min:1'],
            'peneliti_ids.*' => ['distinct', 'integer', 'exists:peneliti,id'],
            'penanggung_jawab_id' => ['required', 'integer', 'in_array:peneliti_ids.*'],

            // Detail analisis (one-to-many, input dinamis).
            'analisis' => ['nullable', 'array', 'max:20'],
            'analisis.*.jenis_analisis' => ['required', 'string', 'max:100'],
            'analisis.*.frekuensi_dominan_hz' => ['nullable', 'numeric', 'min:0', 'max:192000'],
            'analisis.*.tingkat_keyakinan' => ['required', 'numeric', 'between:0,100'],
            'analisis.*.hasil' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'spesies_id.required' => 'Spesies wajib dipilih.',
            'lokasi_id.required' => 'Lokasi wajib dipilih.',
            'tanggal_rekam.before_or_equal' => 'Tanggal rekam tidak boleh di masa depan.',
            'waktu_mulai.date_format' => 'Format waktu harus JJ:MM.',
            'durasi_detik.min' => 'Durasi minimal 1 detik.',
            'peneliti_ids.required' => 'Pilih minimal satu peneliti.',
            'peneliti_ids.min' => 'Pilih minimal satu peneliti.',
            'penanggung_jawab_id.required' => 'Tentukan penanggung jawab rekaman.',
            'penanggung_jawab_id.in_array' => 'Penanggung jawab harus salah satu peneliti yang dipilih.',
            'analisis.*.jenis_analisis.required' => 'Jenis analisis pada setiap baris wajib diisi.',
            'analisis.*.tingkat_keyakinan.between' => 'Tingkat keyakinan harus 0–100 persen.',
        ];
    }
}
