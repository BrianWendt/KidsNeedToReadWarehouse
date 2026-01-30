<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchaseOrder;
use App\Orchid\Layouts\PurchaseOrder\PurchaseOrderSelection;

class ExportController extends Controller
{
    public function purchase_orders(Request $request)
    {
        $query = PurchaseOrder::defaultSort('updated_at', 'DESC')
            ->filters(PurchaseOrderSelection::class)
            ->whereNull('archived_at');

        return view('export.purchase_orders', [
            'purchase_orders' => $query->get(),
        ]);
    }
}
