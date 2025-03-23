<?php

namespace App\Orchid\Screens\Fulfillment;

use App\Models\Fulfillment;
use App\Models\FulfillmentInventory;
use App\Orchid\Layouts\Fulfillment as Layouts;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class FulfillmentViewScreen extends Screen
{
    public $fulfillment;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Fulfillment $fulfillment): iterable
    {

        $quantity = 0;
        $total = 0;

        foreach ($fulfillment->inventory as $inventory) {
            $quantity += $inventory->quantity;
            if ($inventory->book) {
                $total += $inventory->price * $inventory->quantity;
            }
        }

        return [
            'fulfillment' => $fulfillment,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->fulfillment->display;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $links = [
            Link::make(__('Print'))
                ->route('app.fulfillment.print', $this->fulfillment)
                ->icon('printer')
                ->class('btn btn-secondary'),
            self::exportAction('xlsx', __('Export XLSX'))
                ->icon('bs.filetype-xlsx'),
        ];
        if ($this->fulfillment->status != 'shipped') {
            $links[] =
                Link::make(__('Edit Details'))
                    ->route('app.fulfillment.edit', $this->fulfillment)
                    ->icon('pencil-square')
                    ->class('btn btn-primary');
        }

        return $links;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {

        $status = $this->fulfillment ? $this->fulfillment->status : 'new';
        $left = [
            Layout::legend('fulfillment', [

                Sight::make('program_display', __('Program')),

                Sight::make('children_served', __('Children Served'))->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->children_served ? $fulfillment->children_served : '<i>-not set-</i>';
                }),

                Sight::make('organization.name', __('Organization'))
                    ->render(function (Fulfillment $fulfillment) {
                        return Link::make($fulfillment->organization->name)->route('app.organization.view', $fulfillment->organization)->class('text-primary');
                    }),

                Sight::make('organization.ein', __('Organization EIN'))
                    ->render(function (Fulfillment $fulfillment) {
                        return $fulfillment->organization->ein ? $fulfillment->organization->ein : '<i>-not set-</i>';
                    }),

                Sight::make('contact', __('Contact'))->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->contact->display_name ?? '<i>-not set-</i>';
                }),

                Sight::make('shipping_contact', __('Shipping Contact'))->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->shipping_contact->display_name ?? '<i>-not set-</i>';
                }),

                Sight::make('shipping_address_id', __('Shipping Address'))->render(function (Fulfillment $fulfillment) {
                    if ($fulfillment->shipping_address) {
                        return nl2br($fulfillment->shipping_address->display);
                    } else {
                        return '<i>-not set-</i>';
                    }
                }),

                Sight::make('description', __('Description'))->render(function (Fulfillment $fulfillment) {
                    return nl2p($fulfillment->description);
                }),

                Sight::make('tracking', __('Tracking Number'))->render(function (Fulfillment $fulfillment) {
                    if ($fulfillment->tracking) {
                        return $fulfillment->tracking;
                    } else {
                        return '<i>-not set-</i>';
                    }
                })->canSee($status == 'shipped'),
            ]),
        ];
        $right = [];

        switch ($status) {
            case 'new':
            case 'preparing':
                $left[] = Layouts\FulfillmentPreparingLayout::class;
                $right[] = Layouts\FulfillmentInventoryRecordListener::class;
                break;
            case 'pending_shipment':
                $right[] = Layouts\FulfillmentPreparedLayout::class;
                $right[] = Layouts\FulfillmentTrackingLayout::class;
                break;
        }

        return [
            Layout::columns([$left, $right]),
            Layout::modal('ready_to_ship_modal', [
                Layout::view('fulfillment.ready-to-ship-modal'),
            ])->title('Confirm Ready to Ship')
                ->applyButton('Mark as Ready to Ship'),
            new Layouts\InventoryListLayout,
        ];
    }

    public function recordInventory(Fulfillment $fulfillment, FulfillmentInventory $inventory, \App\Http\Requests\StoreFulfillmentInventoryRequest $request)
    {
        if ($fulfillment->status == 'new') {
            $fulfillment->update(['status' => 'preparing']);
        }

        $inventory->fill($request->get('fulfillment_inventory'));
        $inventory->fulfillment_id = $fulfillment->id;
        $inventory->fixed_value = 0;

        if ($inventory->book) {
            $inventory->fixed_value = conditionPrice($inventory->book, $inventory->book_condition);
        }

        $inventory->save();

        \Orchid\Support\Facades\Toast::success(__('Inventory added'));

        return redirect()->route('app.fulfillment.view', $fulfillment);
    }

    public function setReadyToShip()
    {
        foreach ($this->fulfillment->inventory as $inventory) {
            \App\Models\Inventory::create([
                'isbn' => $inventory->isbn,
                'book_condition' => $inventory->book_condition,
                'quantity' => $inventory->quantity * -1,
                'fixed_value' => $inventory->fixed_value,
                'entity_id' => $inventory->fulfillment_id,
                'entity_type' => 'fulfillment',
                'note' => $inventory->note,
            ]);
        }

        $this->fulfillment->update(['status' => 'pending_shipment']);
        \Orchid\Support\Facades\Toast::success(__('Status updated'));

        return redirect()->route('app.fulfillment.view', $this->fulfillment);
    }

    public function backToPreparing()
    {
        $this->fulfillment->update(['status' => 'preparing']);
        \App\Models\Inventory::where(['entity_id' => $this->fulfillment->id, 'entity_type' => 'fulfillment'])->delete();
        \Orchid\Support\Facades\Toast::success(__('Status updated'));

        return redirect()->route('app.fulfillment.view', $this->fulfillment);
    }

    public function setTracking(\App\Http\Requests\SetTrackingRequest $request)
    {
        $this->fulfillment->update(['tracking' => $request->input('tracking'), 'status' => 'shipped']);
        \Orchid\Support\Facades\Toast::success(__('Tracking updated'));

        return redirect()->route('app.fulfillment.view', $this->fulfillment);
    }

    public function markAsShipped()
    {
        $this->fulfillment->update(['status' => 'shipped']);
        \Orchid\Support\Facades\Toast::success(__('Status updated'));

        return redirect()->route('app.fulfillment.view', $this->fulfillment);
    }

    public function deleteInventory($id, Request $request)
    {

        \Orchid\Support\Facades\Toast::success(__('Inventory deleted'));

        return redirect()->route('app.fulfillment.view', $this->fulfillment);
    }

    use \App\Orchid\Screens\Traits\Exports;

    public $export_target = 'fulfillment.inventory';

    public $export_columns = [
        'isbn' => 'ISBN',
        'title' => 'Item',
        'book_condition' => 'Condition',
        'price' => 'Item Value',
        'quantity' => 'Quantity',
        'total' => 'Total',
    ];

    public function exportFilename()
    {
        $ts = time();

        return "fulfillment-{$this->fulfillment->id}-{$ts}";
    }

    public function exportInstance(): \App\Exports\Exports
    {
        return (new \App\Exports\ExportsStatement)->setFullfillment($this->fulfillment);
    }
}
