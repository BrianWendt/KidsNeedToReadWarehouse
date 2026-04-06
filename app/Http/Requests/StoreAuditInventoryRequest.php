<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditInventoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'audit_inventory.audit_id' => 'required|exists:audits,id',
            'audit_inventory.isbn' => 'required|max:30',
            'audit_inventory.quantity' => 'required|integer',
            'audit_inventory.book_condition' => 'required|in:' . implode(',', array_keys(config('options.book_conditions'))),
        ];
    }
}
