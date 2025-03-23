<?php

namespace App\Orchid\Screens\Fulfillment;

use App\Models\Fulfillment;
use Orchid\Screen\Screen;

class FulfillmentCreateScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Fulfillment $fulfillment): iterable
    {
        return [
            'fulfillment' => $fulfillment,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Create Fulfillment');
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
            \App\Orchid\Layouts\Fulfillment\FulfillmentCreateLayout::class,
        ];
    }

    public function save(Fulfillment $fulfillment, \App\Http\Requests\StoreFulfillmentRequest $request)
    {
        $fulfillment->fill($request->get('fulfillment'))->save();

        \Orchid\Support\Facades\Toast::success(__('Fulfillment created'));

        return redirect()->route('app.fulfillment.edit', $fulfillment);
    }
}
