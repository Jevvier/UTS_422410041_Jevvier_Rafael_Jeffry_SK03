<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateSpesiesRequest extends StoreSpesiesRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['nama_ilmiah'] = ['required', 'string', 'max:150',
            Rule::unique('spesies', 'nama_ilmiah')->ignore($this->route('spesies'))];

        return $rules;
    }
}
