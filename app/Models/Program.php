<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $name
 * @property string $display
 * @property string $note
 * @property bool $starred
 */

class Program extends AppModel
{
    use Traits\hasStared;
    
    use \Orchid\Filters\Filterable,
        \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'name',
        'note',
        'starred'
    ];

    protected $allowedSorts = [
        'name',
        'created_at',
        'updated_at'
    ];
}
