<?php

namespace App\Orchid\Screens\Fulfillment;

use App\Models\Fulfillment;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class FulfillmentPrintScreen extends FulfillmentViewScreen
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
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Fulfillment #'.$this->fulfillment->id;
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

                Sight::make('organization.name', __('Organization'))
                    ->render(function (Fulfillment $fulfillment) {
                        return Link::make($fulfillment->organization->name)->route('app.organization.view', $fulfillment->organization)->class('text-primary');
                    }),

                Sight::make('organization.ein', __('Organization EIN'))
                    ->render(function (Fulfillment $fulfillment) {
                        return $fulfillment->organization->ein;
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

                Sight::make('tracking', __('Tracking Number'))->render(function (Fulfillment $fulfillment) {
                    if ($fulfillment->tracking) {
                        return $fulfillment->tracking;
                    } else {
                        return '<i>-not set-</i>';
                    }
                })->canSee($status == 'shipped'),
            ]),
        ];
        $right = [
            Layout::legend('fulfillment', [
                Sight::make('status_display', __('Status')),

                Sight::make('program_display', __('Program')),
                Sight::make('description', __('Description'))->render(function (Fulfillment $fulfillment) {
                    return nl2p($fulfillment->description);
                }),
            ]),
        ];

        return [
            Layout::columns([$left, $right]),

            new \App\Orchid\Layouts\Fulfillment\InventoryListLayout(false),
        ];
    }
}
