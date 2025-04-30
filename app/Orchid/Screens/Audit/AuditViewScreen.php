<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class AuditViewScreen extends Screen
{
    use \App\Orchid\Screens\Traits\Exports;

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

                    self::exportAction('xlsx', __('Export XLSX'))
                        ->icon('bs.filetype-xlsx'),
                ]),
            ]),

            \App\Orchid\Layouts\Audit\AuditInventoryListLayout::class,
        ];
    }

    public $export_target = 'audit.audit_inventory';

    public $export_columns = [
        'isbn' => 'ISBN',
        'book.title' => 'Title',
        'quantity' => 'Quantity',
        'created_at_display' => 'Recorded Date',
    ];

    public function exportFilename()
    {
        $ts = time();

        return "audit-inventory-{$ts}";
    }
}
