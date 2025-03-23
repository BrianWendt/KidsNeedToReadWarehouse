<?php

namespace App\Orchid\Layouts\Fulfillment;

use App\Models\FulfillmentInventory;

use Orchid\Screen\{
    Actions\Link,
    Layouts\Table,
    TD
};

class InventoryListLayout extends Table
{
    public $show_actions = true;
    
    public function __construct($show_actions = true)
    {
        $this->show_actions = $show_actions;
    }

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'fulfillment.inventory';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $fulfillmnent = $this->query->getContent('fulfillment');

        $cols = [


            TD::make('book_label', __('Item'))
                ->cantHide(false)
                ->render(function (FulfillmentInventory $fulfillment_inventory) {
                    if ($fulfillment_inventory->book) {
                        return Link::make($fulfillment_inventory->book_label)
                            ->route('app.book.edit', $fulfillment_inventory->book);
                    } else {
                        return Link::make($fulfillment_inventory->book_label)
                            ->type(\Orchid\Support\Color::WARNING())
                            ->route('app.book.create', $fulfillment_inventory->isbn);
                    }
                }),

            TD::make('book_condition_display', __('Condition'))
                ->cantHide(false)
                ->width('200px'),

            TD::make('book_value', __('Item Value'))
                ->cantHide(false)
                ->render(function (FulfillmentInventory $fulfillment_inventory) {
                    if ($fulfillment_inventory->book) {
                        return money_format($fulfillment_inventory->price, 2);
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
                ->render(function (FulfillmentInventory $fulfillment_inventory) {
                    if ($fulfillment_inventory->book) {
                        $value = $fulfillment_inventory->price * $fulfillment_inventory->quantity;
                        return money_format($value, 2);
                    } else {
                        return '-';
                    }
                })
                ->align(TD::ALIGN_RIGHT)
                ->width('100px'),
        ];

        switch ($fulfillmnent->status && $this->show_actions) {
            case 'new':
            case 'preparing':
                $cols[] = TD::make('actions', __('Actions'))
                    ->render(function (FulfillmentInventory $fulfillment_inventory) {
                        return Link::make(__('Edit'))
                            ->icon('pencil-square')
                            ->route('app.fulfillment_inventory.edit', $fulfillment_inventory);
                    })
                    ->canSee($fulfillmnent->status != 'shipped')
                    ->cantHide(false);
        }

        return $cols;
    }

    public function total(): array
    {
        return [
            TD::make('total')
                ->style('font-weight: bold')
                ->align(TD::ALIGN_RIGHT)
                ->colspan(3)
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
