<?php

declare(strict_types=1);

namespace App\Filters\Types;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\BaseHttpEloquentFilter;

class Search extends BaseHttpEloquentFilter
{
    public function run(Builder $builder): Builder
    {
        $string = $this->getHttpValue();
        $values = explode(' ', $string);
        foreach ($values as $value) {
            $builder = $builder->where($this->column, 'like', '%' . $value . '%');
        }

        return $builder;
    }
}
