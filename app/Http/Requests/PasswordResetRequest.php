<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:6'],
        ];    
    }

    public function messages()
    {
        return [
            'password.required' => 'New password is required.',
            'password.string' => 'New password must be a string type.',
            'password.min' => 'New password must be minimum of 6 characters.',
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'New password',
        ];
    }
}
