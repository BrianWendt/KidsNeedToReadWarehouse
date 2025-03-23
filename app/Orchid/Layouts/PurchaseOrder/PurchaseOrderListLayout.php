<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use App\Models\PurchaseOrder;

use Orchid\Screen\{
    Actions\Link,
    Fields\Group,
    Fields\Input,
    Fields\Select,
    Layouts\Table,
    TD
};

use App\Orchid\Filters\PurchaseOrderStatusFilter;

class PurchaseOrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'purchase_orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'PO #')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (PurchaseOrder $purchase_order) {
                    return
                        Link::make('#' . $purchase_order->id)
                        ->route('app.purchase_order.view', $purchase_order);
                }),

            TD::make('contact', __('Contact'))
                ->sort()
                ->cantHide()
                ->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->contact_id ? $purchase_order->contact->display : $purchase_order->organization->display;
                }),

            TD::make('updated_at', __('Last Updated'))
                ->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->updated_at->format('Y-m-d g:i A');
                }),

            TD::make('actions', __('Actions'))
                ->width('260px')
                ->cantHide()
                ->render(function (PurchaseOrder $purchase_order) {
                    return Group::make([
                        Link::make(__('View'))
                            ->route('app.purchase_order.view', $purchase_order)
                            ->icon('eye'),
                        Link::make(__('Print'))
                            ->route('app.purchase_order.print', $purchase_order)
                            ->icon('printer'),
                    ]);
                })
        ];
    }
}
