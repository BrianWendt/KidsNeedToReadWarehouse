<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Fulfillment;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $inventory = \App\Models\Inventory::select(DB::raw('sum(quantity) as quantity, isbn, book_condition'))
            ->groupBy(['isbn', 'book_condition'])
            ->get();

        $book_stats = [
            'book_count' => $inventory->where('quantity', '>', 0)->count(),
            'inventory_count_new' => $inventory->where('book_condition', 'new')->sum('quantity'),
            'inventory_count_like_new' => $inventory->where('book_condition', 'like_new')->sum('quantity'),
            'inventory_count_used' => $inventory->where('book_condition', 'used')->sum('quantity'),
        ];

        $fulfillment_stats = [
            'incomplete' => Fulfillment::whereNotIn('status', ['shipped', 'cancelled'])->count(),
            'complete' => Fulfillment::where('status', 'shipped')->count(),
        ];

        return [
            'book_stats' => new Repository($book_stats),
            'fulfillment_stats' => new Repository($fulfillment_stats),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Dashboard';
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::split([
                [
                    Layout::legend('book_stats', [
                        Sight::make('book_count', __('Unique Books')),
                        Sight::make('inventory_count_new', __('New Books')),
                        Sight::make('inventory_count_like_new', __('Like New Books')),
                        Sight::make('inventory_count_used', __('Used Books')),
                    ])->title(__('Book Stats')),
                ],
                [
                    Layout::legend('fulfillment_stats', [
                        Sight::make('incomplete', __('Incomplete')),
                        Sight::make('complete', __('Complete')),
                    ])->title(__('Fulfillment Stats')),
                ],
            ]),

        ];
    }
}
