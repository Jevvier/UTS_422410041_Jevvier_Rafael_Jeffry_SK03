<?php

namespace App\Http\Requests;

use App\Models\Spesies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpesiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_spesies_id' => ['required', 'integer', 'exists:kategori_spesies,id'],
            'nama_ilmiah' => ['required', 'string', 'max:150', Rule::unique('spesies', 'nama_ilmiah')],
            'nama_umum' => ['required', 'string', 'max:150'],
            'status_konservasi' => ['required', Rule::in(array_keys(Spesies::STATUS_KONSERVASI))],
            'habitat_ids' => ['required', 'array', 'min:1'],
            'habitat_ids.*' => ['distinct', 'integer', 'exists:habitats,id'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_spesies_id.required' => 'Kategori wajib dipilih.',
            'nama_ilmiah.required' => 'Nama ilmiah wajib diisi.',
            'nama_ilmiah.unique' => 'Nama ilmiah sudah terdaftar.',
            'nama_umum.required' => 'Nama umum wajib diisi.',
            'habitat_ids.required' => 'Pilih minimal satu habitat.',
            'habitat_ids.min' => 'Pilih minimal satu habitat.',
            'habitat_ids.*.exists' => 'Habitat yang dipilih tidak valid.',
        ];
    }
}
