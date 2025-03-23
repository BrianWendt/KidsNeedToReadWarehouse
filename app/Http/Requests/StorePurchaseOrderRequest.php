<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseOrderRequest extends FormRequest
{
    public function rules()
    {

        return [
            'purchase_order.contact_id' => 'exists:contacts,id',
            'purchase_order.contact_id' => Rule::when(not_empty('purchase_order.contact_id'), ['exists:contacts,id']),
            'purchase_order.address_id' => Rule::when(not_empty('purchase_order.address_id'), ['exists:addresses,id']),
            'purchase_order.description' => 'max:600',
            'purchase_order.organization_id' => 'required|exists:organizations,id',
            'purchase_order.received_date' => 'date',
        ];
    }

    public function messages()
    {
        return [
            'purchase_order.contact_id.exists' => 'The selected contact does not exist.',
            'purchase_order.contact_id.exists' => 'The selected shipping contact does not exist.',
            'purchase_order.address_id.exists' => 'The selected shipping address does not exist.',
            'purchase_order.organization_id.required' => 'The organization is required.',
            'purchase_order.organization_id.exists' => 'The selected organization does not exist.',
            'purchase_order.received_date.date' => 'The received date must be a valid date.',
        ];
    }
}
