<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Orchid\Layouts\PurchaseOrder\PurchaseOrderSelection;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

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
                ->filters(PurchaseOrderSelection::class)
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
            Link::make(__('Export Overview'))
                ->icon('download')
                ->route('export.purchase_orders', $_GET)
                ->class('btn icon-link btn-secondary')
                ->target('_blank'),
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
            PurchaseOrderSelection::class,
            \App\Orchid\Layouts\PurchaseOrder\PurchaseOrderListLayout::class,
            Layout::view('hidden-input', ['name' => 'contact_id', 'value' => request()->input('contact_id'), 'form' => 'filters']),
            Layout::view('hidden-input', ['name' => 'organization_id', 'value' => request()->input('organization_id'), 'form' => 'filters']),
        ];
    }
}
