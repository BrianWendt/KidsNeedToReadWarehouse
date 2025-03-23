<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $name
 * @property bool $starred
 * @property string display
 */
trait hasStared
{
    public function display(): Attribute
    {
        $name = $this->name;
        if ($this->starred) {
            $name = '★ '.$name;
        }

        return Attribute::make(
            get: fn () => $name,
        );
    }

    public function scopeAsOptions(Builder $builder)
    {
        return $builder->orderBy('starred', 'desc')
            ->orderBy('name');
    }
}
