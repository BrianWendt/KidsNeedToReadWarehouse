<?php

namespace App\Orchid\Layouts\Audit;

use App\Models\AuditInventory;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuditInventoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'audit.audit_inventory';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('created_at', __('Date'))
                ->render(function (AuditInventory $inventory) {
                    return $inventory->created_at->format('Y-m-d g:i A');
                })
                ->cantHide()
                ->width('160px'),

            TD::make('isbn', __('ISBN'))
                ->cantHide()
                ->width('150px'),

            TD::make('title', __('Title'))
                ->render(function (AuditInventory $inventory) {
                    return $inventory->book ? $inventory->book->title : '-';
                }),

            TD::make('quantity', __('Quantity'))
                ->cantHide()
                ->render(function (AuditInventory $inventory) {
                    return number_format($inventory->quantity);
                })
                ->align(TD::ALIGN_CENTER)
                ->width('100px'),
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
