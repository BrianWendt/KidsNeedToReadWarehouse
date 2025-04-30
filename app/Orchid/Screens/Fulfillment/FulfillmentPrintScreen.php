<?php

namespace App\Orchid\Screens\Fulfillment;

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
        return 'Fulfillment #' . $this->fulfillment->id;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('fulfillment.print'),
        ];
    }
}
