<?php

namespace App\Orchid\Screens\Inventory;

use App\Http\Requests\StoreInventoryRequest;

use App\Models\Inventory;

use App\Orchid\Layouts\Inventory as InventoryLayouts;

use Orchid\Screen\Screen;

/**
 * @property Inventory $inventory
 */
class InventoryEditScreen extends Screen
{

    public $inventory;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Inventory $inventory): iterable
    {
        return [
            'inventory' => $inventory,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Edit Inventory Record');
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
            InventoryLayouts\InventoryEditLayout::class
        ];
    }

    public function save(Inventory $inventory, StoreInventoryRequest $request)
    {
        $inventory->fill($request->get('inventory'));
        $inventory->save();

        $inventory->entity->touch();

        \Orchid\Support\Facades\Toast::success("Inventory Updaed.");
        return redirect()->route($inventory->entity->viewRoute(), $inventory->entity_id);
    }
}
