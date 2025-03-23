<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method Builder asOptions()
 */
class AppModel extends Model
{
    use \Orchid\Screen\AsSource, \Orchid\Filters\Filterable, \App\Models\Traits\HasTimestamps;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if ($this->timestamps) {
            $this->appends[] = 'created_at_display';
            $this->appends[] = 'updated_at_display';
            $this->appends[] = 'created_at_date';
            $this->appends[] = 'updated_at_date';
        }
    }

    /**
     * @return Builder
     */
    public function scopeDefaultSortRaw(Builder $builder, string $column)
    {
        if (empty($builder->getQuery()->orders)) {
            $builder->orderByRaw($column);
        }

        return $builder;
    }

    /**
     * @param string $pluck
     * @return array
     */
    public static function getAsOptions($pluck, $key = null)
    {
        $instance = new static;
        $key = $key ?? $instance->primaryKey;
        return $instance->asOptions()->get()->pluck($pluck, $key)->toArray();
    }

    /**
     * @return Builder
     */
    public function scopeAsOptions(Builder $builder)
    {
        return $builder;
    }
}
