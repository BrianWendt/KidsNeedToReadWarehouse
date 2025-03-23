<?php

namespace App\Orchid\Screens\Inventory;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Book;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Orchid\Layouts\Book as BookLayouts;
use App\Orchid\Layouts\Inventory as InventoryLayouts;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

/**
 * @property Inventory $inventory
 * @property Book $book
 */
class InventoryRecordScreen extends Screen
{
    public $inventory;

    public $book;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(string $isbn): iterable
    {
        $book = Book::where('isbn', $isbn)->first();
        // Inventory instance for the form
        $inventory = new Inventory([
            'isbn' => $isbn,
        ]);
        $inventory_list = Inventory::where('isbn', $isbn)->orderBy('created_at', 'DESC')->get();

        $stats = [
            'new_count' => $inventory_list->where('book_condition', 'new')->sum('quantity'),
            'like_new_count' => $inventory_list->where('book_condition', 'like_new')->sum('quantity'),
            'used_count' => $inventory_list->where('book_condition', 'used')->sum('quantity'),
        ];
        $stats['total_count'] = $stats['new_count'] + $stats['used_count'];

        return [
            'isbn' => $isbn,
            'book' => $book,
            'inventory' => $inventory,
            'inventory_list' => $inventory_list,
            'stats' => new Repository($stats),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Record Inventory');
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
        $left = [
            InventoryLayouts\InventoryRecordLayout::class,
        ];
        $right = [];

        if ($this->book) {
            $right[] = BookLayouts\BookLegend::layout();
            if ($this->book->volume_id) {
                $right[] = Layout::view('book.thumbnail');
            }
        } else {
            $right[] = BookLayouts\BookNotFoundLayout::class;
        }

        return [
            Layout::split([
                $left,
                $right,
            ]),
            InventoryLayouts\InventoryGlanceLayout::class,
            InventoryLayouts\InventoryListLayout::class,
        ];
    }

    public function save(Inventory $inventory, StoreInventoryRequest $request)
    {
        $inventory->fill($request->get('inventory'));
        $inventory->entity_type = 'po';
        $inventory->save();

        PurchaseOrder::find($inventory->entity_id)->touch();

        \Orchid\Support\Facades\Toast::success("You have successfully recorded {$inventory->quantity} of `{$inventory->isbn}`.");
        if ($inventory->book) {
            $inventory->book->touch();

            return redirect()->route('app.purchase_order.view', $inventory->entity_id);
        } else {
            return redirect()->route('app.book.create', $inventory->isbn);
        }
    }
}
