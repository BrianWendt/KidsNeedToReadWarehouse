<?php

namespace App\Orchid\Screens\Audit;

use App\Http\Requests\StoreAuditInventoryRequest;
use App\Models\Audit;
use App\Models\AuditInventory;
use App\Orchid\Layouts\Audit as AuditLayouts;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

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

        return raw_html('<b>'.__('Audit').':</b> '.__('Record Inventory'));
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
        \Orchid\Support\Facades\Toast::success("You have successfully recorded {$audit_inventory->quantity} of `{$audit_inventory->isbn}`.");

        return redirect()->route('app.audit.record', $this->audit);
    }

    public function bulkSave(Request $request)
    {
        $string = $request->input('bulk');
        $lines = explode("\n", $string);

        foreach ($lines as $line) {
            $parts = explode("\t", $line);
            if (count($parts) != 2) {
                \Orchid\Support\Facades\Toast::error("Invalid line: $line");

                continue;
            }
            if (! is_numeric($parts[1])) {
                \Orchid\Support\Facades\Toast::error("Invalid quantity: $parts[1]");

                continue;
            }
            $audit_inventory = new AuditInventory;
            $audit_inventory->isbn = trim($parts[0]);
            $audit_inventory->quantity = trim($parts[1]);
            $audit_inventory->audit_id = $this->audit->id;
            $audit_inventory->save();
        }

        \Orchid\Support\Facades\Toast::success('You have successfully recorded the inventory.');

        return redirect()->route('app.audit.record', $this->audit);
    }
}
