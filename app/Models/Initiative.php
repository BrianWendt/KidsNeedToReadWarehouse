<?php

namespace App\Models;

/**
 * @property string $name
 * @property string $display
 * @property string $note
 * @property bool $starred
 */

class Initiative extends AppModel
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
