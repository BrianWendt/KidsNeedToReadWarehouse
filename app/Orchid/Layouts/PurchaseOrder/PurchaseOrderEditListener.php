<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;

class PurchaseOrderEditListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'purchase_order.contact_id',
    ];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            \App\Orchid\Layouts\PurchaseOrder\PurchaseOrderEditLayout::class,
        ];
    }

    /**
     * Update state
     *
     * @param  \Orchid\Screen\Repository  $repository
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $repository->set($request->input());

        return $repository;
    }
}
