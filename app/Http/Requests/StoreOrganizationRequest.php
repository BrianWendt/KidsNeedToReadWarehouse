<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'organization.name' => 'required|max:128',
            'organization.ein' => 'max:20',
            'organization.primary_contact_id' => 'exists:contacts,id',
            'organization.note' => 'max:600',
            'organization.starred' => 'boolean',
        ];
    }
}
