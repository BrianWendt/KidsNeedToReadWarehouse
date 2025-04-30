<?php

namespace App\Orchid\Layouts\Fulfillment;

use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;

class FulfillmentEditListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['fulfillment.shipping_contact_id'];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            \App\Orchid\Layouts\Fulfillment\FulfillmentEditLayout::class,
        ];
    }

    /**
     * Update state
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $repository->set($request->input());

        return $repository;
    }
}
