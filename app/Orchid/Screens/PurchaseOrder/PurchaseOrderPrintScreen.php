<?php

namespace App\Orchid\Screens\PurchaseOrder;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PurchaseOrderPrintScreen extends PurchaseOrderViewScreen
{
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
            Layout::view('purchase_order.print'),
        ];
    }
}
