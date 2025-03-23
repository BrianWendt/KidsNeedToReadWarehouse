<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreTelephoneRequest extends FormRequest {
    public function rules() {
 
        return [
            'telephone.contact_id' => 'required|exists:contacts,id',
            'telephone.name' => 'required|max:128',
            'telephone.number' => 'required|max:20',
            'telephone.extension' => 'max:20',
            'telephone.note' => 'max:200',
        ];
    }
}