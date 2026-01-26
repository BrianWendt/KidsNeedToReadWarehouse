<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;

class ContactSearchFilter extends Filter
{
    /**
     * The displayable name of the filter.
     */
    public function name(): string
    {
        return 'Search Contacts';
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
        $search = request()->input('search');
        if ($search) {
            $builder->where(function ($query) use ($search) {
                /*
                $query->whereRaw('MATCH(first_name, last_name) AGAINST(? IN BOOLEAN MODE)', [$search . '*'])
                      ->orderByRaw('MATCH(first_name, last_name) AGAINST(? IN BOOLEAN MODE) DESC', [$search . '*']);
                    */
                $query->whereRaw('soundex(first_name) LIKE ?', [soundex($search) . '%'])
                    ->orWhereRaw('soundex(last_name) LIKE ?', [soundex($search) . '%'])
                    ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
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
            Fields\Input::make('search')
                ->type('text')
                ->placeholder('Search by First or Last Name')
                ->title('Search Contacts')
                ->value(request()->input('search', '')),
        ];
    }
}
