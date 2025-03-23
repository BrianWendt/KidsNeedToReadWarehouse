<?php

namespace App\Exports;

use App\Exports\Entity\Cell;

/**
 * @property array $data
 * @property \App\Models\Fulfillment $fulfillment
 */

class ExportsStatement extends Exports
{
    public $fulfillment;

    public function setFullfillment(\App\Models\Fulfillment $fulfillment)
    {
        $this->fulfillment = $fulfillment;
        return $this;
    }

    public function beforeExport(\OpenSpout\Writer\AbstractWriter $writer): void
    {
        $fulfillment = $this->fulfillment;

        // Fulfillment Address
        $rows = [
            $this->keyValueRow('Fulfillment', '#' . $fulfillment->id),
            $this->keyValueRow('Status', $fulfillment->status_display),
            $this->keyValueRow('Organization', $fulfillment->organization->name),
            $this->keyValueRow('EIN', $fulfillment->organization->ein ?? '-'),
        ];

        if ($fulfillment->shipping_contact_id && $fulfillment->shipping_contact) {
            $rows[] = $this->keyValueRow('Shipping Contact', $fulfillment->shipping_contact->display_name);
        }
        if ($fulfillment->shipping_address_id && $fulfillment->shipping_address) {
            $rows[] = $this->keyValueRow('Shipping Address', $fulfillment->shipping_address->display);
        }

        // get totals of all inventory items by condition_group
        $totals = [];
        $total = 0;
        foreach ($fulfillment->inventory as $inventory) {
            $condition_group = $inventory->book_condition_group;
            if (!isset($totals[$condition_group])) {
                $totals[$condition_group] = 0;
            }
            $totals[$condition_group] += $inventory->total;
            $total += $inventory->total;
        }

        $rows[0]->addCell(Cell::make('Total')->bold()->alignRight()->build());
        $rows[0]->addCell(Cell::make($total)->alignRight()->formatMoney()->build());

        $ridx = 1;
        foreach($totals as $condition_group => $sum){
            $rows[$ridx]->addCell(Cell::make($condition_group . ' Total')->bold()->alignRight()->build());
            $rows[$ridx]->addCell(Cell::make($sum)->alignRight()->formatMoney()->build());
            $ridx++;
        }

        $writer->addRows($rows);

        // Fulfillment Inventory
        $writer->addRow(new \OpenSpout\Common\Entity\Row([
            Cell::make('ISBN')->bold()->build(),
            Cell::make('Item')->bold()->build(),
            Cell::make('Condition')->bold()->build(),
            Cell::make('Item Value')->bold()->alignRight()->build(),
            Cell::make('Quantity')->bold()->alignCenter()->build(),
            Cell::make('Total')->bold()->alignRight()->build()
        ]));

        $writer->addRow(new \OpenSpout\Common\Entity\Row([]));

        $total_count = 0;
        $total_value = 0;
        foreach ($fulfillment->inventory as $inventory) {
            $row = new \OpenSpout\Common\Entity\Row([
                Cell::make($inventory->isbn)->build(),
                Cell::make($inventory->title)->build(),
                Cell::make($inventory->book_condition_display)->build(),
                Cell::make($inventory->price)->alignRight()->formatMoney()->build(),
                Cell::make($inventory->quantity)->alignCenter()->build(),
                Cell::make($inventory->total)->alignRight()->formatMoney()->build()
            ]);
            $writer->addRow($row);
            $total_count += $inventory->quantity;
            $total_value += $inventory->total;
        }

        $c = count($this->data['columns']);
        $row = new \OpenSpout\Common\Entity\Row([
            Cell::make('')->build(),
            Cell::make('')->build(),
            Cell::make('')->build(),
            Cell::make('Total')->bold()->alignRight()->build(),
            Cell::make($total_count)->bold()->build(),
            Cell::make($total_value)->bold()->build()
        ]);
        $writer->addRow($row);
    }

    public function beforeExportXLSX(\OpenSpout\Writer\XLSX\Writer $writer): void
    {
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Fulfillment Statement');
        $sheet->setColumnWidth(18, 1);
        $sheet->setColumnWidth(30, 2);
        $sheet->setColumnWidthForRange(16, 3, count($this->data['columns']));
    }

    public function keyValueRow($key, $value)
    {
        return new \OpenSpout\Common\Entity\Row([
            Cell::make($key)->bold()->alignRight()->build(),
            Cell::make($value)->build(),
        ]);
    }
}
