<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreEmailRequest extends FormRequest {
    public function rules() {
 
        return [
            'email.contact_id' => 'required|exists:contacts,id',
            'email.name' => 'required|max:128',
            'email.address' => 'required|email|max:128',
            'email.note' => 'max:200',
        ];
    }
}