<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'unique:lib_offices'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.unique' => 'Title has already exist.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Title of office',
        ];
    }
}
