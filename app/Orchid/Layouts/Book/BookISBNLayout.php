<?php

namespace App\Orchid\Layouts\Book;

use App\Orchid\Fields\ISBN;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;

class BookISBNLayout extends Rows
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
            ISBN::make('book.isbn')
                ->maxlength(20)
                ->minlength(3)
                ->required()
                ->title(__('ISBN'))
                ->set('autofocus', true)
                ->id('isbn-search')
                ->onEnter('isbn-search-button'),

            Button::make(__('Lookup'))
                ->method('search')
                ->icon('magnifier')
                ->class('btn btn-success')
                ->parameters([
                    'isbn' => 'book.isbn',
                ])
                ->id('isbn-search-button'),
        ];
    }
}
