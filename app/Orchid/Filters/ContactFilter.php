<?php

namespace App\Orchid\Filters;

use App\Orchid\Fields;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class ContactFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return 'Filter Contact';
    }

    /**
     * The array of matched parameters.
     */
    public function parameters(): ?array
    {
        return ['contact_id'];
    }

    /**
     * Apply to a given Eloquent query builder.
     */
    public function run(Builder $builder): Builder
    {
        if (request()->has('contact_id')) {
            $builder->where('contact_id', request()->input('contact_id'));
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
        return [];
    }
}
