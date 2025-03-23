<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreAuditInventoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'audit_inventory.isbn' => 'required|max:20',
            'audit_inventory.quantity' => 'required|integer',
        ];
    }
}
