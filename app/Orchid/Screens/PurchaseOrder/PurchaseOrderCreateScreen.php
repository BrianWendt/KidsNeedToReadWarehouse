<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use Orchid\Screen\Screen;

class PurchaseOrderCreateScreen extends Screen
{
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
        return __('Create Purchase Order');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\PurchaseOrder\PurchaseOrderCreateLayout::class
        ];
    }

    public function save(PurchaseOrder $purchase_order, \App\Http\Requests\StorePurchaseOrderRequest $request)
    {
        $purchase_order->fill($request->get('purchase_order'))->save();

        \Orchid\Support\Facades\Toast::success(__('Purchase Order created'));

        return redirect()->route('app.purchase_order.view', $purchase_order);
    }
}
