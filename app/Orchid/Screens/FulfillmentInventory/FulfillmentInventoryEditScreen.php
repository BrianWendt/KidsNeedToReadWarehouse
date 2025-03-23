<?php

namespace App\Orchid\Screens\FulfillmentInventory;

use App\Models\FulfillmentInventory;
use Orchid\Screen\Screen;

class FulfillmentInventoryEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(FulfillmentInventory $fulfillment_inventory): iterable
    {
        return [
            'fulfillment_inventory' => $fulfillment_inventory,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Edit Fulfillment Line');
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
            \App\Orchid\Layouts\FulfillmentInventory\FulfillmentInventoryEditLayout::class
        ];
    }

    public function save(FulfillmentInventory $fulfillment_inventory, \App\Http\Requests\StoreFulfillmentInventoryRequest $request)
    {
        $fulfillment_inventory->fill($request->get('fulfillment_inventory'))->save();

        \Orchid\Support\Facades\Toast::success(__('Fulfillment updated'));

        return redirect()->route('app.fulfillment.view', $fulfillment_inventory->fulfillment);
    }

    public function delete(FulfillmentInventory $fulfillment_inventory)
    {
        $fulfillment_inventory->delete();

        \Orchid\Support\Facades\Toast::success(__('Fulfillment updated'));

        return redirect()->route('app.fulfillment.view', $fulfillment_inventory->fulfillment);
    }
}
