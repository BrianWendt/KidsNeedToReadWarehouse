<?php

namespace App\Orchid\Layouts\Inventory;

use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;

class InventoryRecordListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['inventory.isbn'];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            InventoryRecordLayout::class,
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
        $isbn = $request->get('inventory.isbn');
        $book = \App\Models\Book::where('isbn', $isbn)->first();
        $title = $book->title ?? '-not in database-';

        $repository->set($request->input());
        $repository->set('title', $title);

        return $repository;
    }
}
