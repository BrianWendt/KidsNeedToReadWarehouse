<?php

namespace App\Orchid\Layouts\Inventory;

use App\Models\Inventory;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MissingBooksLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'books';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('isbn', __('ISBN'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->width('160px')
                ->render(function (Inventory $inventory) {
                    return Link::make($inventory->isbn)
                        ->route('app.book.create', ['isbn' => $inventory->isbn]);
                }),

            TD::make('quantity', __('Quantity'))
                ->width('80px'),

            TD::make('actions', __('Actions'))
                ->render(function (Inventory $inventory) {
                    return Group::make([
                        Link::make(__('Add Book to Database'))
                            ->route('app.book.create', ['isbn' => $inventory->isbn])
                            ->icon('plus-circle'),
                    ]);
                }),

        ];
    }
}
