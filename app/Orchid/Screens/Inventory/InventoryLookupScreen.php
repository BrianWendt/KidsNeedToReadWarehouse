<?php

namespace App\Orchid\Screens\Inventory;

use App\Orchid\Layouts\Book\BookISBNLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class InventoryLookupScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Lookup ISBN');
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
            BookISBNLayout::class
        ];
    }

    public function search(Request $request)
    {
        $isbn = $request->input('book.isbn');
        return redirect()->route('app.inventory.record', $isbn);
    }
}
