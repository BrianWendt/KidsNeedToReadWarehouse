<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

/**
 * @property PurchaseOrder $purchase_order
 * @property int $quantity
 * @property float $total
 */
class PurchaseOrderViewScreen extends Screen
{
    public $purchase_order;

    public $quantity;

    public $total;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PurchaseOrder $purchase_order): iterable
    {
        if ($purchase_order->archived_at) {
            abort(410, 'This purchase order has been archived.');
        }

        $quantity = 0;
        $total = 0;

        foreach ($purchase_order->inventory as $inventory) {
            $quantity += $inventory->quantity;
            if ($inventory->book) {
                $total += $inventory->price * $inventory->quantity;
            }
        }

        return [
            'purchase_order' => $purchase_order,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->purchase_order->display;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        if ($this->purchase_order->status != 'shipped') {
            return [
                Link::make(__('Print'))
                    ->route('app.purchase_order.print', $this->purchase_order)
                    ->icon('printer')
                    ->class('btn btn-secondary'),
                Link::make(__('Edit Details'))
                    ->route('app.purchase_order.edit', $this->purchase_order)
                    ->icon('pencil-square')
                    ->class('btn btn-primary'),
            ];
        } else {
            return [];
        }
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $left = [
            Layout::legend('purchase_order', [
                Sight::make('id', 'ID'),

                Sight::make('contact.organization.name', __('Organization'))
                    ->render(function (PurchaseOrder $purchase_order) {
                        return Link::make($purchase_order->organization->name)->route('app.organization.view', $purchase_order->organization)->class('text-primary');
                    }),

                Sight::make('received_date', __('Received Date')),

                Sight::make('contact', __('Contact'))->render(function (PurchaseOrder $purchase_order) {
                    if ($purchase_order->contact_id) {
                        return Link::make($purchase_order->contact->full_name)->route('app.contact.view', $purchase_order->contact)->class('text-primary');
                    } else {
                        return '<i>-not set-</i>';
                    }
                }),

                Sight::make('note', __('Note'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->note ? nl2p($purchase_order->note) : '<i>-</i>';
                }),
            ]),

        ];

        $quick_links = [];
        foreach (usedBookCodes() as $code => $label) {
            $quick_links[] = Link::make(str_replace('.', '․', $label)) // Replace periods with middle dots to prevent Orchid doing weird things
                ->route('app.inventory.record', ['isbn' => $code, 'purchase_order_id' => $this->purchase_order->id, 'book_condition' => 'used'])
                ->class('btn btn-sm')
                ->icon('book');
        }
        $quick_links_group = Group::make($quick_links)->widthColumns('auto auto auto auto');

        $right = [
            Layout::rows([$quick_links_group])->title('Used Book Check In'),
            \App\Orchid\Layouts\Book\BookISBNLayout::class,
        ];

        return [
            Layout::split([$left, $right]),

            \App\Orchid\Layouts\PurchaseOrder\InventoryListLayout::class,
        ];
    }

    public function search(Request $request)
    {
        $isbn = $request->input('book.isbn');

        return redirect()->route('app.inventory.record', ['isbn' => $isbn, 'purchase_order_id' => $this->purchase_order->id, 'book_condition' => $this->purchase_order->book_condition]);
    }
}
