<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarStoreUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'avatar_new' => ['required', 'mimes:jpg,jpeg,bmp,png,svg', 'max:2048'], //max of 2mb
        ];
    }

    public function messages()
    {
        return [
            'avatar_new.required' => 'New image is required. Please browse and select.',
            'avatar_new.mimes' => 'Invalid image format. Allower are the following: jpg, jpeg, bmp, png, svg.',
            'avatar_new.max' => 'New image must not greater than 2mb.',
            //'avatar_new.unique' => 'New image has already exist.',
        ];
    }

    public function attributes()
    {
        return [
            'New image is' => 'New image',
        ];
    }
}
