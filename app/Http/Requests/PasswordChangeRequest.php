<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password_old' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ];    
    }

    public function messages()
    {
        return [
            'password_old.required' => 'Current password is required.',

            'password.required' => 'New password is required.',
            'password.string' => 'New password must be a string type.',
            'password.min' => 'New password must be minimum of 6 characters.',
            'password.confirmed' => 'Password does not match.',

            'password_confirmation.required' => 'Confirmation password is required.',
        ];
    }

    public function attributes()
    {
        return [
            'password_old' => 'Current password',
            'password' => 'New password',
            'password_confirmation' => 'Confirmation password',
        ];
    }
}
