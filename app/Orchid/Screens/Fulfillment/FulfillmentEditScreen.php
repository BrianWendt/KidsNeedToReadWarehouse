<?php

namespace App\Orchid\Screens\Fulfillment;

use App\Models\Fulfillment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class FulfillmentEditScreen extends Screen
{
    public $fulfillment;

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
        return __('Edit Fulfillment:') . '#' . $this->fulfillment->id;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Mark as `Cancelled`'))
                ->icon('trash')
                ->method('cancel')
                ->confirm(__('Are you sure you want to cancel this fulfillment?'))
                ->class('btn icon-link btn-danger'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\Fulfillment\FulfillmentEditListener::class,
        ];
    }

    public function save(Fulfillment $fulfillment, \App\Http\Requests\StoreFulfillmentRequest $request)
    {
        $fulfillment->fill($request->get('fulfillment'))->save();

        \Orchid\Support\Facades\Toast::success(__('Fulfillment updated'));

        return redirect()->route('app.fulfillment.view', $fulfillment);
    }

    public function cancel(Fulfillment $fulfillment)
    {
        $fulfillment->status = 'cancelled';
        $fulfillment->save();

        \Orchid\Support\Facades\Toast::success(__('Fulfillment cancelled'));

        return redirect()->route('app.fulfillment.list');
    }
}
