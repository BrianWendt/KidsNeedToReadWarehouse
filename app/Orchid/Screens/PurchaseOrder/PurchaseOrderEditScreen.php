<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use Orchid\Screen\Screen;

use Orchid\Support\Facades\Layout;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;

class PurchaseOrderEditScreen extends Screen
{

    public $purchase_order;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PurchaseOrder $purchase_order): iterable
    {
        return [
            'purchase_order' => $purchase_order,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Edit PurchaseOrder:') . '#' . $this->purchase_order->id;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('`Archive` Purchase Order'))
                ->icon('trash')
                ->method('archive')
                ->confirm(__('Are you sure you want to archive this Purchase Order?'))
                ->class('btn btn-danger')
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
            \App\Orchid\Layouts\PurchaseOrder\PurchaseOrderEditListener::class,
            Layout::modal('organizationModal', [
                Layout::rows([
                    Select::make('purchase_order.organization_id')
                        ->title(__('Organization'))
                        ->fromQuery(\App\Models\Organization::defaultOrder(), 'display')
                        ->required()
                        ->help(__('Other fields will not be saved.')),
                ])
            ])
        ];
    }

    public function save(PurchaseOrder $purchase_order, \App\Http\Requests\StorePurchaseOrderRequest $request)
    {
        $purchase_order->fill($request->get('purchase_order'))->save();

        \Orchid\Support\Facades\Toast::success(__('Purchase Order updated'));

        return redirect()->route('app.purchase_order.view', $purchase_order);
    }

    public function changeOrganization(PurchaseOrder $purchase_order, \App\Http\Requests\StorePurchaseOrderRequest $request)
    {
        if ($request->input('purchase_order.organization_id') != $purchase_order->organization_id) {
            $purchase_order->fill($request->get('purchase_order'));
            $purchase_order->contact_id = null;
            $purchase_order->address_id = null;
            $purchase_order->telephone_id = null;
            $purchase_order->email_id = null;
            $purchase_order->save();
            \Orchid\Support\Facades\Toast::success(__('Organization updated'));
        }


        return redirect()->route('app.purchase_order.edit', $purchase_order);
    }

    public function archive(PurchaseOrder $purchase_order)
    {
        $purchase_order->archived_at = now();
        $purchase_order->save();

        \Orchid\Support\Facades\Toast::success(__('Purchase Order archived'));

        return redirect()->route('app.purchase_order.list');
    }
}
