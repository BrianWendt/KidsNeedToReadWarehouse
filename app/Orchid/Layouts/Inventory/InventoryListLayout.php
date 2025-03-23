<?php

namespace App\Orchid\Layouts\Inventory;

use App\Models\Inventory;

use Orchid\Screen\{
    Layouts\Table,
    TD
};

class InventoryListLayout extends Table
{

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'inventory_list';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('created_at', __('Date'))
                ->render(function (Inventory $inventory) {
                    return $inventory->created_at->format('Y-m-d g:i A');
                })
                ->cantHide(),

            TD::make('entity_display', __('Source / Fulfillment'))
                ->cantHide(),

            TD::make('book_condition_display', __('Condition'))
                ->cantHide(),

            TD::make('price', __('Unity Price'))
                ->cantHide()
                ->render(function (Inventory $inventory) {
                    return number_format($inventory->price, 2);
                }),

            TD::make('quantity', __('Quantity'))
                ->cantHide()
                ->render(function (Inventory $inventory) {
                    return number_format($inventory->quantity);
                }),
        ];
    }

    protected function textNotFound(): string
    {
        if (count(request()->query()) !== 0) {
            return __('No results found for your current filters');
        }

        return __('No inventory yet.');
    }

    protected function subNotFound(): string
    {
        if (count(request()->query()) !== 0) {
            return __('Try adjusting your filter settings or removing it altogether to see more data');
        }

        return '';
    }
}
