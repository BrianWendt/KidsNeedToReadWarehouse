<?php

namespace App\Orchid\Screens\Book;

use App\Models\Book;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class BookListScreen extends Screen
{
    public $books;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'books' => Book::filters()->defaultSort('updated_at', 'DESC')->paginate(25),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Books Database');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Lookup'))
                ->icon('bs.upc-scan')
                ->route('app.inventory.lookup')
                ->class('btn btn-info'),

            self::exportAction('csv', __('Export CSV'))
                ->icon('bs.filetype-csv'),
            self::exportAction('xlsx', __('Export XLSX'))
                ->icon('bs.filetype-xlsx'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\Book\BookListLayout::class,
        ];
    }

    use \App\Orchid\Screens\Traits\Exports;

    public $export_target = 'books';

    public $export_columns = [
        'title' => 'Title',
        'isbn' => 'ISBN',
        'author' => 'Author',
        'categories' => 'Categories',
        'retail_price_display' => 'Retail Price',
        'page_count' => 'Page Count',
        'inventory_quantity' => 'Total Inventory',
        'new_inventory_quantity' => 'New Inventory',
        'used_inventory_quantity' => 'Used Inventory',
    ];

    public function exportFilename()
    {
        $ts = time();

        return "books-{$ts}";
    }
}
