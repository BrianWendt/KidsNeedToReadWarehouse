<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'inventory.isbn' => 'required|max:30',
            'inventory.quantity' => 'required|integer',
            'inventory.entity_id' => 'required|integer',
            'inventory.book_condition' => ['required', Rule::in(array_keys(config('options.book_conditions')))],
            'inventory.fixed_value' => Rule::when(not_empty('book.fixed_value'), 'numeric|min:0.01'),
            'inventory.note' => 'max:200',
        ];
    }
}
