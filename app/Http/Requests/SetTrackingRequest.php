<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetTrackingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tracking' => 'max:20|min:6',
        ];
    }
}
