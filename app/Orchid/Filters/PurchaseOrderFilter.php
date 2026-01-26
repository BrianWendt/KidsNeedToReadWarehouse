<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;

class PurchaseOrderFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return 'Filter Purchase Orders';
    }

    /**
     * The array of matched parameters.
     */
    public function parameters(): ?array
    {
        return ['filter.contact_name'];
    }

    /**
     * Apply to a given Eloquent query builder.
     */
    public function run(Builder $builder): Builder
    {
        $contact_name = request()->input('filter.contact_name');
        if ($contact_name) {
            $builder->whereHas('contact', function (Builder $query) use ($contact_name) {
                $query->fullNameSearch($contact_name);
            });
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
            Fields\Input::make('filter.contact_name')
                ->type('text')
                ->value(request()->input('filter.contact_name'))
                ->title('Contact Name')
                ->placeholder('Contact Name'),
        ];
    }
}
