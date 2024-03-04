<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'unique:lib_departments'],
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
            'title' => 'Title of department',
        ];
    }
}
