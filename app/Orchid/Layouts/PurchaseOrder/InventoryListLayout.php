<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use App\Models\Inventory;

use Orchid\Screen\Actions;
use Orchid\Screen\{
    Actions\Link,
    Layouts\Table,
    Repository,
    TD
};
use PaginationHelper;

/**
 * @property \Orchid\Screen\Repository $query
 */

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
    protected $target = 'purchase_order.inventory';

    public function build(Repository $repository)
    {

        $inventory = $repository->getContent('purchase_order.inventory');
        if ($inventory->count() > 100) {
            $c = $inventory->count();
            $inventory = $inventory->slice(0, 100);
            $repository->set('purchase_order.inventory', $inventory);
            $this->title = 'NOTE: Displaying first 100 items of ' . $c . ' total items.';
        }

        return parent::build($repository);
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {

        $cols = [

            TD::make('isbn', __('ISBN'))
                ->cantHide(false)
                ->width('100px')
                ->render(function (Inventory $inventory) {
                    return Link::make($inventory->isbn)
                        ->route('app.inventory.record', $inventory->isbn);
                }),

            TD::make('book_label', __('Item'))
                ->cantHide(false)
                ->render(function (Inventory $inventory) {
                    if ($inventory->book) {
                        return Link::make($inventory->book_label)
                            ->route('app.inventory.record', $inventory->isbn);
                    } else {
                        return Link::make($inventory->book_label)
                            ->type(\Orchid\Support\Color::WARNING())
                            ->route('app.book.create', $inventory->isbn);
                    }
                }),

            TD::make('book_condition_display', __('Condition'))
                ->cantHide(false)
                ->width('200px'),

            TD::make('book_value', __('Item Value'))
                ->cantHide(false)
                ->render(function (Inventory $inventory) {
                    if ($inventory->book) {
                        return money_format($inventory->price, 2);
                    } else {
                        return '-';
                    }
                })
                ->align(TD::ALIGN_RIGHT)
                ->width('100px'),

            TD::make('quantity', __('Quantity'))
                ->align(TD::ALIGN_CENTER)
                ->cantHide(false)
                ->width('100px'),

            TD::make('total', __('Total'))
                ->cantHide(false)
                ->render(function (Inventory $inventory) {
                    if ($inventory->book) {
                        $value = $inventory->price * $inventory->quantity;
                        return money_format($value, 2);
                    } else {
                        return '-';
                    }
                })
                ->align(TD::ALIGN_RIGHT)
                ->width('100px'),

            TD::make('edit')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Inventory $inventory) {
                    return Actions\Link::make(__('Edit'))
                        ->route('app.inventory.edit', $inventory->id)
                        ->icon('pencil');
                }),
        ];

        return $cols;
    }

    public function total(): array
    {
        return [
            TD::make('total')
                ->style('font-weight: bold')
                ->align(TD::ALIGN_RIGHT)
                ->colspan(4)
                ->render(fn () => 'Total:'),
            TD::make('quantity')
                ->style('font-weight: bold')
                ->align(TD::ALIGN_CENTER),
            TD::make('total')
                ->style('font-weight: bold')
                ->align(TD::ALIGN_RIGHT)
                ->render(function () {
                    return money_format($this->query->getContent('total'));
                }),
            TD::make('edit')
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
