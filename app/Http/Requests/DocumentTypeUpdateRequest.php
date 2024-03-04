<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Services\Hasher;
use Illuminate\Foundation\Http\FormRequest;

class DocumentTypeUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = Hasher::decode($this->hashid);
        return [
            'title' => ['required', 
                Rule::unique('lib_document_types')->ignore($id)],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.unique' => 'Unable to update, title has already exist.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Title of document type.',
        ];
    }
}
