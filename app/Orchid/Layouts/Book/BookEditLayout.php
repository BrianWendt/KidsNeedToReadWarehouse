<?php

namespace App\Orchid\Layouts\Book;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class BookEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('book.isbn')->type('isbn')
                ->title(__('ISBN'))
                ->readonly(),

            Input::make('book.volume_id')->type('hidden'),

            Input::make('book.title')
                ->maxlength(128)
                ->required()
                ->title(__('Title')),

            Input::make('book.author')
                ->maxlength(128)
                ->title(__('Author')),

            Input::make('book.retail_price')
                ->type('number')
                ->mask('999.99')
                ->min(0.01)
                ->maxlength(999.99)
                ->step(0.01)
                ->title(__('Retail Price'))
                ->help(__('Retail price of the book as it appears on the book.')),

            Input::make('book.fixed_value')
                ->type('number')
                ->mask('999.99')
                ->min(0.01)
                ->maxlength(999.99)
                ->step(0.01)
                ->title(__('Fixed Price'))
                ->help(__('Overrides calculated price based on condition.')),

            Input::make('book.page_count')
                ->type('number')
                ->title(__('Page Count'))
                ->help(__('Estimate Page Count')),

            Button::make(__('Save'))
                ->class('btn icon-link btn-success')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }
}
