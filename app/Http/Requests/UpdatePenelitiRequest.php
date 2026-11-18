<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdatePenelitiRequest extends StorePenelitiRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['email'] = ['required', 'email', 'max:150',
            Rule::unique('peneliti', 'email')->ignore($this->route('peneliti'))];

        return $rules;
    }
}
