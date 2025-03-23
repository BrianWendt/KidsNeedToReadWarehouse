<?php

namespace App\Orchid\Screens\Fulfillment;

use App\Models\Fulfillment;

use Orchid\Screen\{
    Actions\Link,
    Screen
};

use App\Orchid\Filters\FulfillmentStatusFilter;

class FulfillmentListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'fulfillments' => Fulfillment::defaultSort('updated_at', 'desc')
                ->filters([FulfillmentStatusFilter::class])
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Fulfillments');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Create Fulfillment'))
                ->icon('plus-circle')
                ->route('app.fulfillment.create')
                ->class('btn btn-primary')
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
            \App\Orchid\Layouts\Fulfillment\FulfillmentListLayout::class
        ];
    }
}
