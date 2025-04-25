<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function rules()
    {
        $book = $this->route('book');
        $id = $book ? $book->id : null;

        return [
            'book.title' => 'required|max:128',
            'book.isbn' => 'required|max:30|min:1|unique:books,isbn,'.$id,
            'book.author' => 'max:128',
            'book.retail_price' => Rule::when(not_empty('book.retail_price'), 'numeric|min:0.01'),
            'book.fixed_value' => Rule::when(not_empty('book.fixed_value'), 'numeric|min:0.01'),
            'book.page_count' => Rule::when(not_empty('book.page_count'), 'integer|min:1'),
        ];
    }

    public function messages()
    {
        return [
            'book.title.required' => 'A title is required.',
            'book.title.max' => 'The title may not be greater than 128 characters.',
            'book.isbn.required' => 'An ISBN is required.',
            'book.isbn.max' => 'The ISBN may not be greater than 20 characters.',
            'book.isbn.unique' => 'The ISBN has already been taken.',
            'book.author.max' => 'The author may not be greater than 128 characters.',
            'book.retail_price.numeric' => 'The retail price must be a number.',
            'book.retail_price.min' => 'The retail price must be at least 0.01.',
            'book.fixed_value.numeric' => 'The fixed price must be a number.',
            'book.fixed_value.min' => 'The fixed price must be at least 0.01.',
            'book.page_count.integer' => 'The page count must be an integer.',
            'book.page_count.min' => 'The page count must be at least 1.',
        ];
    }
}
