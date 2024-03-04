<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {   
        return [
            'username' => ['required', 'string', 'max:255', 
                            Rule::unique('users')->ignore($this->user_id)],
            'email' => ['required', 'string', 'email', 'max:255', 
                            Rule::unique('users')->ignore($this->user_id)],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:10'],
            'department' => ['required', 'string', 'max:10'],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'Unable to update, username has already exist.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Unable to update, email has already exist.',
            
            'last_name.required' => 'Last name is required.',
            'first_name.required' => 'First name is required.',
            'middle_name.required' => 'Middle name is required.',
            'office.required' => 'Office location is required.',
            'department.required' => 'Department location is required.',
        ];
    }

    // public function attributes()
    // {
    //     return [
    //         'title' => 'Title of department',
    //     ];
    // }
}
