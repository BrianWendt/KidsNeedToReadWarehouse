<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class AuditViewScreen extends Screen
{
    public $audit;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Audit $audit): iterable
    {
        $audit->audit_inventory = $audit->audit_inventory()->orderBy('created_at', 'desc')->paginate(50);

        return [
            'audit' => $audit,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->audit->label . " (Audit #{$this->audit->id})";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Edit Audit Details'))
                ->icon('pencil-square')
                ->route('app.audit.edit', $this->audit)
                ->class('btn icon-link btn-primary'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        if (request()->get('export') == 'xlsx') {
            return [$this->export()];
        }

        return [
            Layout::legend('audit', [
                Sight::make('label', __('Label')),
                Sight::make('created_at', __('Created At'))->render(function (Audit $audit) {
                    return $audit->created_at->format('Y-m-d g:i A');
                }),
                Sight::make('updated_at', __('Updated At'))->render(function (Audit $audit) {
                    return $audit->updated_at->format('Y-m-d g:i A');
                }),
                Sight::make('closed_at', __('Closed At'))->render(function (Audit $audit) {
                    return $audit->closed_at ? $audit->closed_at->format('Y-m-d g:i A') : 'open';
                }),
            ]),

            Layout::rows([
                Group::make([
                    Link::make(__('Add Inventory'))
                        ->icon('plus-circle')
                        ->route('app.audit.record', $this->audit)
                        ->type(\Orchid\Support\Color::SUCCESS()),

                    Link::make(__('Export XLSX'))
                        ->route('app.audit.view', [$this->audit, 'export' => 'xlsx'])
                        ->type(\Orchid\Support\Color::PRIMARY())
                        ->icon('bs.filetype-xlsx'),
                ]),
            ]),

            \App\Orchid\Layouts\Audit\AuditInventoryListLayout::class,
        ];
    }

    protected function export()
    {
        $diff = $this->getDiff();
        $csv = "ISBN\tTitle\tInventory Quantity\tAudit Quantity\tDiff\n";
        $csv .= str_putcsv($diff, "\t");

        return Layout::view('export.audit-inventory', [
            'diff' => $diff,
            'csv' => $csv,
        ]);
    }

    protected function getDiff()
    {
        $books = DB::table('books')->pluck('title', 'isbn');
        $audit_inventory = DB::table('audit_inventory')
            ->selectRaw('isbn, book_condition, SUM(quantity) as quantity')
            ->where('audit_id', $this->audit->id)
            ->groupBy('isbn', 'book_condition')
            ->get();
        $inventory = DB::table('inventory')
            ->selectRaw('isbn, book_condition, SUM(quantity) as quantity')
            ->groupBy('isbn', 'book_condition')
            ->get();

        // combine $audit_inventory and $inventory by isbn
        $combined = [];
        foreach ($audit_inventory as $item) {
            $key = $item->isbn; // . '-' . $item->book_condition;
            $combined[$key] = [
                'isbn' => $item->isbn,
                'title' => '',
                // 'book_condition' => $item->book_condition,
                'inventory_quantity' => 0,
                'audit_quantity' => $item->quantity,

            ];
        }

        foreach ($inventory as $item) {
            $key = $item->isbn; // . '-' . $item->book_condition;
            if (isset($combined[$key])) {
                $combined[$key]['inventory_quantity'] = $item->quantity;
            } else {
                $combined[$key] = [
                    'isbn' => $item->isbn,
                    'title' => '',
                    // 'book_condition' => $item->book_condition,
                    'inventory_quantity' => $item->quantity,
                    'audit_quantity' => 0,
                ];
            }
        }

        // iterate and create a diff field and add book title
        foreach ($combined as $key => $item) {
            $combined[$key]['diff'] = $item['audit_quantity'] - $item['inventory_quantity'];
            $combined[$key]['title'] = $books[$item['isbn']] ?? 'Unknown';
        }

        return $combined;
    }

    public function exportFilename()
    {
        $ts = time();

        return "audit-inventory-{$ts}";
    }
}
