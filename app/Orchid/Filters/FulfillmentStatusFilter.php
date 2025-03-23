<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class FulfillmentStatusFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return 'Filter by Fulfillemt Status';
    }

    /**
     * The array of matched parameters.
     */
    public function parameters(): ?array
    {
        return [];
    }

    /**
     * Apply to a given Eloquent query builder.
     */
    public function run(Builder $builder): Builder
    {
        $id = request()->input('filter.id');
        $status = request()->input('filter.status');
        if ($status && empty($id)) {
            $status_list = config('options.fulfillment_statuses');
            if (! empty($status_list[$status])) {
                $builder->where('status', $status);
            }
            switch ($status) {
                case 'fulfilled':
                    $builder->where('status', 'shipped');
                    break;
                case 'unfulfilled':
                    $builder->whereIn('status', ['new', 'preparing', 'pending_shipment']);
                    break;
            }
        } elseif (empty($id)) {
            $builder->whereNot('status', 'cancelled');
        }

        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {

        return [
            Select::make('status')
                ->options(self::options())
                ->title('Fulfillment Status'),
        ];
    }

    public static function options()
    {
        $options = [
            'new' => '',
            'fulfilled' => 'Fulfilled',
            'unfulfilled' => 'Unfulfilled',
        ];
        $options += config('options.fulfillment_statuses');

        return $options;
    }
}
