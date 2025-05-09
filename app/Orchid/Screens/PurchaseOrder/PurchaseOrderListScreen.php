<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class PurchaseOrderListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'purchase_orders' => PurchaseOrder::defaultSort('updated_at', 'DESC')
                ->whereNull('archived_at')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Purchase Orders');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Create Purchase Order'))
                ->icon('plus-circle')
                ->route('app.purchase_order.create')
                ->class('btn icon-link btn-primary'),
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
            \App\Orchid\Layouts\PurchaseOrder\PurchaseOrderListLayout::class,
        ];
    }
}
