<?php

namespace App\Models\Traits;

use App\Builders\ExportableBuilder;

trait Exports
{

    public function newEloquentBuilder($query): ExportableBuilder
    {
        return new ExportableBuilder($query);
    }
}
