<?php

namespace App\Orchid\Layouts\Book;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layouts\Rows;

class BookNotFoundLayout extends Rows
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
            Label::make('book_not_found')
                ->tag('h3')
                ->title(__('Book Not Found'))
                ->help(__('No book was found with that ISBN in the database. You may record inventory of the book before adding the book to the database.')),

            Link::make(__('Add Book'))
                ->class('btn icon-link btn-success')
                ->icon('bs.plus-circle')
                ->route('app.book.create', ['isbn' => request()->isbn]),
        ];
    }
}
