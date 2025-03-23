<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreFulfillmentRequest extends FormRequest {
    public function rules() {
 
        return [
            'fulfillment.contact_id' => 'exists:contacts,id',
            'fulfillment.program_id' => 'exists:programs,id',
            'fulfillment.initiative_id' => 'exists:initiatives,id',
            'fulfillment.shipping_contact_id' => 'exists:contacts,id',
            'fulfillment.shipping_address_id' => Rule::when(function(\Illuminate\Support\Fluent $fluent){
                return $fluent->get('fulfillment.shipping_address_id') > 0;
            }, ['exists:addresses,id']),
            'fulfillment.description' => 'max:600',
            'fulfillment.organization_id' => 'required|exists:organizations,id',
        ];
    }

    public function messages(){
        return [
            'fulfillment.contact_id.exists' => 'The selected contact does not exist.',
            'fulfillment.shipping_contact_id.exists' => 'The selected shipping contact does not exist.',
            'fulfillment.shipping_address_id.exists' => 'The selected shipping address does not exist.',
            'fulfillment.organization_id.required' => 'The organization is required.',
            'fulfillment.organization_id.exists' => 'The selected organization does not exist.',
        ];
    }
}