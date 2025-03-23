<?php

namespace App\Orchid\Layouts\Fulfillment;

use Illuminate\Http\Request;

use Orchid\Screen\{
    Layouts\Listener,
    Repository
};

class FulfillmentInventoryRecordListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['book_id'];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            FulfillmentInventoryRecordLayout::class,
        ];
    }

    /**
     * Update state
     *
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $repository->set($request->input());
        
        $book = \App\Models\Book::find($request->input('book_id'));
        if($book){
            $repository->set('fulfillment_inventory.isbn', $book->isbn);
        }
        return $repository;
    }
}
