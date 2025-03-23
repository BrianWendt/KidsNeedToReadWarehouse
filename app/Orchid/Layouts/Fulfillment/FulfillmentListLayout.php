<?php

namespace App\Orchid\Layouts\Fulfillment;

use App\Models\Fulfillment;
use App\Orchid\Filters\FulfillmentStatusFilter;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FulfillmentListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'fulfillments';

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
                ->render(function (Fulfillment $fulfillment) {
                    return
                        Link::make('#'.$fulfillment->id)
                            ->route('app.fulfillment.view', $fulfillment);
                }),

            TD::make('organization.name', __('Organization'))
                ->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->organization->name;
                }),

            TD::make('status', __('Status'))
                ->sort()
                ->cantHide()
                ->filter(Select::make()->options(FulfillmentStatusFilter::options()))
                ->filterValue(function ($status) {
                    return FulfillmentStatusFilter::options()[$status] ?? $status;
                })
                ->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->status_display;
                }),

            TD::make('updated_at', __('Last Updated'))
                ->render(function (Fulfillment $fulfillment) {
                    return $fulfillment->updated_at->format('Y-m-d g:i A');
                }),

            TD::make('actions', __('Actions'))
                ->width('260px')
                ->cantHide()
                ->render(function (Fulfillment $fulfillment) {
                    return Link::make(__('View'))
                        ->route('app.fulfillment.view', $fulfillment)
                        ->icon('eye');
                }),
        ];
    }
}
