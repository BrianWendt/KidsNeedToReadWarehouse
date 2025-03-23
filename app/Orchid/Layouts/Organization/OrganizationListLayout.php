<?php

namespace App\Orchid\Layouts\Organization;

use App\Models\Organization;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrganizationListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'organizations';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', __('Name'))
                ->filter(\Orchid\Screen\Fields\Input::make())
                ->sort()
                ->cantHide()
                ->render(function (Organization $organization) {
                    return Link::make($organization->display)
                        ->route('app.organization.view', $organization);
                }),

            TD::make('updated_at', __('Update date'))
                ->sort()
                ->render(function ($model) {
                    return $model->updated_at->format('Y-m-d H:i A');
                }),

            TD::make('actions', __('Actions'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->width('260px')
                ->render(function (Organization $organization) {
                    return \Orchid\Screen\Fields\Group::make([
                        Link::make('View')
                            ->icon('eye')
                            ->route('app.organization.view', $organization),
                        Link::make('Edit')
                            ->icon('pencil-square')
                            ->route('app.organization.edit', $organization),
                    ]);
                }),
        ];
    }
}
