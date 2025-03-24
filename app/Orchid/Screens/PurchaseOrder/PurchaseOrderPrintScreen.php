<?php

namespace App\Orchid\Screens\PurchaseOrder;

use App\Models\PurchaseOrder;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PurchaseOrderPrintScreen extends PurchaseOrderViewScreen
{
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
        $quantity = $this->quantity;
        $total = $this->total;

        $left = [
            Layout::legend('purchase_order', [

                Sight::make('contact.organization.name', __('Organization'))
                    ->render(function (PurchaseOrder $purchase_order) {
                        return Link::make($purchase_order->organization->name)->route('app.organization.view', $purchase_order->organization)->class('text-primary');
                    }),

                Sight::make('received_date', __('Received Date'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->received_date ?? '<i>-</i>';
                }),

                Sight::make('contact', __('Donor Name'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->contact->full_name;
                })->canSee($this->purchase_order->contact_id > 0),

                Sight::make('address', __('Donor Address'))->render(function (PurchaseOrder $purchase_order) {
                    return nl2br($purchase_order->address->display);
                })->canSee($this->purchase_order->address_id > 0),

                Sight::make('telephone', __('Donor Telephone'))->render(function (PurchaseOrder $purchase_order) {
                    return nl2br($purchase_order->telephone->display);
                })->canSee($this->purchase_order->telephone_id > 0),

                Sight::make('email', __('Donor Email'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->email->address;
                })->canSee($this->purchase_order->email_id > 0),
            ]),

        ];

        $right = [

            Layout::legend('purchase_order', [
                Sight::make('organization.ein', __('Organization EIN'))
                    ->render(function (PurchaseOrder $purchase_order) {
                        return $purchase_order->organization->ein;
                    })->canSee($this->purchase_order->organization->ein != ''),

                Sight::make('contact.ein', __('Contact EIN'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->contact->ein;
                })->canSee(! empty($this->purchase_order->contact->ein)),

                Sight::make('note', __('Note'))->render(function (PurchaseOrder $purchase_order) {
                    return $purchase_order->note ? nl2p($purchase_order->note) : '<i>-</i>';
                })->canSee($this->purchase_order->note != ''),

                Sight::make('total', __('Total'))->render(function (PurchaseOrder $purchase_order) use ($quantity, $total) {
                    return $quantity.' items<br/>$'.number_format($total, 2);
                }),
            ]),
        ];

        return [
            Layout::columns([$left, $right]),

            new \App\Orchid\Layouts\PurchaseOrder\InventoryListLayout(true),
        ];
    }
}
