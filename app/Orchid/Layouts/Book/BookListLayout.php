<?php

namespace App\Orchid\Layouts\Book;

use App\Models\Book;

use Orchid\Screen\{
    Actions\Link,
    Fields\Input,
    Layouts\Table,
    TD
};

class BookListLayout extends Table
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
                ->cantHide()
                ->filter(Input::make())
                ->width('160px')
                ->render(function (Book $book) {
                    return Link::make($book->isbn)
                        ->icon('bs.plus-slash-minus')
                        ->route('app.inventory.record', $book->isbn);
                }),

            TD::make('title', __('Title'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('new_inventory_quantity', __('New'))
                ->width('80px')
                ->render(function (Book $book) {
                    return number_format($book->new_inventory_quantity);
                })
                ->align(TD::ALIGN_CENTER),

            TD::make('like_new_inventory_quantity', __('Like New'))
                ->width('100px')
                ->render(function (Book $book) {
                    return number_format($book->like_new_inventory_quantity);
                })
                ->align(TD::ALIGN_CENTER),

            TD::make('used_inventory_quantity', __('Used'))
                ->width('80px')
                ->render(function (Book $book) {
                    return number_format($book->used_inventory_quantity);
                })
                ->align(TD::ALIGN_CENTER),

            TD::make('actions', __('Actions'))
                ->sort()
                ->cantHide()
                ->width('160px')
                ->render(function (Book $book) {
                    return \Orchid\Screen\Fields\Group::make([
                        Link::make(__('Inventory'))
                            ->icon('bs.plus-slash-minus')
                            ->route('app.inventory.record', $book->isbn)
                    ]);
                }),
        ];
    }
}
