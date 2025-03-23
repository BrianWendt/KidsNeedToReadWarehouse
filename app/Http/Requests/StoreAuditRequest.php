<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'audit.label' => 'required|max:120|min:2',
        ];
    }
}
