<?php

namespace App\Orchid\Layouts\Book;

use Orchid\Support\Facades\Layout;

use Orchid\Screen\{
    Layouts\Legend,
    Sight
};

use Orchid\Screen\Actions\Link;

class BookLegend
{

    public static function layout(): Legend
    {

        $sights = [];
        $sights[] = Sight::make('title', __('Title'));
        $sights[] = Sight::make('author', __('Author'));
        $sights[] = Sight::make('categories', __('Categories'));
        $sights[] = Sight::make('retail_price_display', __('Retail Price'));

        $sights[] = Sight::make('fixed_value_display', __('Fixed Value'));

        $sights[] = Sight::make('page_count', __('Page Count'));
        $sights[] = Sight::make('', __('Actions'))
            ->render(function ($book) {
                return Link::make(__('Edit Book Details'))
                    ->route('app.book.edit', $book->id)
                    ->icon('pencil');
            });

        return Layout::legend('book', $sights);
    }
}
