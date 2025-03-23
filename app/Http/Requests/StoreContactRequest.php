<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function rules()
    {

        return [
            'contact.first_name' => 'required|max:64',
            'contact.last_name' => 'required|max:64',
            'contact.preferred_name' => 'max:128',
            'contact.title' => 'max:128',
            'contact.note' => 'max:600',
            'contact.organization_id' => 'required|exists:organizations,id',
        ];
    }
}
