<?php

namespace App\Orchid\Layouts\Audit;

use App\Models\Audit;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuditListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'audits';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', '#')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Audit $audit) {
                    return
                        Link::make('#'.$audit->id)
                            ->route('app.audit.view', $audit);
                })
                ->width('100px'),

            TD::make('label', __('Label'))
                ->sort()
                ->cantHide()
                ->render(function (Audit $audit) {
                    return $audit->label;
                }),

            TD::make('created_at', __('Created At'))
                ->render(function (Audit $audit) {
                    return $audit->created_at->format('Y-m-d g:i A');
                })
                ->width('160px'),

            TD::make('closed_at', __('Closed At'))
                ->render(function (Audit $audit) {
                    return $audit->closed_at ? $audit->closed_at->format('Y-m-d g:i A') : 'open';
                })
                ->width('160px'),

            TD::make('actions', __('Actions'))
                ->width('260px')
                ->cantHide()
                ->render(function (Audit $audit) {
                    return Group::make([
                        Link::make(__('Edit'))
                            ->route('app.audit.edit', $audit)
                            ->icon('pencil'),
                        Link::make(__('Record Inventory'))
                            ->route('app.audit.record', $audit)
                            ->icon('plus-circle')
                            ->type(\Orchid\Support\Color::SUCCESS()),
                    ]);
                }),
        ];
    }
}
