<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreFulfillmentInventoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'fulfillment_inventory.isbn' => 'required|max:20',
            'fulfillment_inventory.quantity' => 'required|integer|min:1',
            'fullfillment_inventory.fixed_value' => Rule::when(not_empty('book.fixed_value'), 'numeric|min:0.01'),
            'fulfillment_inventory.book_condition' => ['required', Rule::in(array_keys(config('options.book_conditions')))],
        ];
    }
}
