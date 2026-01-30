<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;

class CreatedAtFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return 'Filter Created At';
    }

    /**
     * The array of matched parameters.
     */
    public function parameters(): ?array
    {
        return ['created_at'];
    }

    /**
     * Apply to a given Eloquent query builder.
     */
    public function run(Builder $builder): Builder
    {
        if (request()->has('created_at.start')) {
            $builder->where('created_at', '>=', request()->input('created_at.start'));
        }
        if (request()->has('created_at.end')) {
            $builder->where('created_at', '<=', request()->input('created_at.end'));
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
            Fields\DateRange::make('created_at')
                ->title('Created At')
                ->format('Y-m-d'),
        ];
    }
}
