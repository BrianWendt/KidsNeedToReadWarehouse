<?php

namespace App\Orchid\Screens\Reports;

use Orchid\Support\Facades\Layout;

use Orchid\Screen\{
    Repository,
    Screen,
    Sight
};

use Illuminate\Support\Facades\DB;

class InventoryReport extends Screen
{

    public $stats;
    public $total;
    public $conditions;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {

        $all_books = DB::table('books')->get();
        $books = [];
        foreach ($all_books as $book) {
            $books[$book->isbn] = $book;
        }
        $inventory = DB::table('inventory')
            ->groupBy('isbn', 'book_condition')
            ->select('isbn', 'book_condition', DB::raw('SUM(quantity) as quantity'))
            ->whereNull('deleted_at')
            ->get();

        $stats = [];
        $conditions = config('options.book_conditions');
        foreach ($conditions as $key => $value) {
            $stats[$key] = 0;
        }
        $total = 0;

        foreach ($inventory as $item) {
            $book = $books[$item->isbn] ?? null;
            if ($book) {
                $price = conditionPrice($book, $item->book_condition);
                $stats[$item->book_condition] += $price * $item->quantity;
                $total += $price * $item->quantity;
            }
        }

        foreach ($stats as $key => $value) {
            $stats[$key] = '$' . number_format($value, 2);
        }

        $total = '$' . number_format($total, 2);

        return [
            'stats' => new Repository($stats),
            'total' => $total,
            'conditions' => $conditions
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Inventory Report');
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
        $values = [];
        foreach ($this->conditions as $key => $value) {
            $values[] = Sight::make('stats.' . $key, "{$value} Taxable Value");
        }
        $values[] = Sight::make('total', 'Total Taxable Value');
        return [
            Layout::legend('', $values),
        ];
    }
}
