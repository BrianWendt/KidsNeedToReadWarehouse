<?php

namespace App\Exports;

use App\Exports\Entity\Cell;

/**
 * @property array $data
 * @property \App\Models\PurchaseOrder $purchase_order
 */

class ExportsPurchaseOrder extends Exports
{
    public $purchase_order;

    public function setPurchaseOrder(\App\Models\PurchaseOrder $purchase_order)
    {
        $this->purchase_order = $purchase_order;
        return $this;
    }

    public function beforeExport(\OpenSpout\Writer\AbstractWriter $writer): void
    {
        $po = $this->purchase_order;
        
    }
}