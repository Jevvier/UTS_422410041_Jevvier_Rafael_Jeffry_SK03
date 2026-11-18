<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateKategoriRequest extends StoreKategoriRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['nama'] = ['required', 'string', 'max:100',
            Rule::unique('kategori_spesies', 'nama')->ignore($this->route('kategori'))];

        return $rules;
    }
}
