<?php

namespace App\Orchid\Screens\Audit;

use App\Http\Requests\StoreAuditInventoryRequest;
use App\Models\Audit;
use App\Models\AuditInventory;
use App\Orchid\Layouts\Audit as AuditLayouts;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class AuditInventoryRecordScreen extends Screen
{
    public $audit_inventory;

    public $audit;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Audit $audit): iterable
    {
        $audit_inventory = new AuditInventory;
        $audit_inventory->audit_id = $audit->id;

        return [
            'audit' => $audit,
            'audit_inventory' => $audit_inventory,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Audit: Record Inventory');

        return raw_html('<b>' . __('Audit') . ':</b> ' . __('Record Inventory'));
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
            \Orchid\Support\Facades\Layout::split([
                AuditLayouts\AuditInventoryRecordLayout::class,
                AuditLayouts\AuditInventoryBulkRecordLayout::class,
            ]),

        ];
    }

    public function save(AuditInventory $audit_inventory, StoreAuditInventoryRequest $request)
    {
        $audit_inventory->fill($request->get('audit_inventory'));
        $audit_inventory->audit_id = $this->audit->id;
        $audit_inventory->save();
        Alert::success("You have successfully recorded {$audit_inventory->quantity} of `{$audit_inventory->isbn}`.");

        return redirect()->route('app.audit.record', $this->audit);
    }

    public function bulkSave(Request $request)
    {
        $string = $request->input('bulk');
        $lines = explode("\n", $string);

        $now = now();

        $inserts = [];
        foreach ($lines as $line) {
            if (trim($line) == '') {
                continue;
            }
            $parts = explode("\t", $line);
            if (count($parts) != 2) {
                Alert::error("Invalid line: $line");

                continue;
            }
            if (! is_numeric($parts[1])) {
                Alert::error("Invalid quantity: $parts[1]");

                continue;
            }

            if (strlen($parts[0]) > 20) {
                Alert::error("ISBN too long: $parts[0]");

                continue;
            }

            $inserts[] = [
                'audit_id' => $this->audit->id,
                'isbn' => trim($parts[0]),
                'quantity' => trim($parts[1]),
                'book_condition' => $request->input('book_condition'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        AuditInventory::insert($inserts);

        Alert::success('You have successfully recorded the inventory.');

        return redirect()->route('app.audit.record', $this->audit);
    }
}
