<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest {
    public function rules() {
 
        return [
            'address.contact_id' => 'required|exists:contacts,id',
            'address.name' => 'required|max:128',
            'address.street1' => 'required|max:128',
            'address.street2' => 'max:128',
            'address.city' => 'required|max:80',
            'address.state' => 'required|max:2',
            'address.zipcode' => 'required|max:5',
        ];
    }
}