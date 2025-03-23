<?php

namespace App\Models;

/**
 * @property string $name
 * @property string $display
 * @property string $note
 * @property bool $starred
 */
class Program extends AppModel
{
    use \Illuminate\Database\Eloquent\SoftDeletes,
        \Orchid\Filters\Filterable;
    use Traits\hasStared;

    protected $fillable = [
        'name',
        'note',
        'starred',
    ];

    protected $allowedSorts = [
        'name',
        'created_at',
        'updated_at',
    ];
}
