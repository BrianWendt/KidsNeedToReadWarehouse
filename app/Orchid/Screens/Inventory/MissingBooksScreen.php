<?php

namespace App\Orchid\Screens\Inventory;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Screen;

class MissingBooksScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'books' => Inventory::whereDoesntHave('book')
                ->select(DB::raw('isbn, sum(quantity) as quantity'))
                ->orderBy('isbn')
                ->groupBy('isbn')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Missing Books');
    }

    public function description(): ?string
    {
        return __('Books that are missing from database but have inventory.');
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
            \App\Orchid\Layouts\Inventory\MissingBooksLayout::class,
        ];
    }
}
