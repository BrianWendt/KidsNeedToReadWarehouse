<?php

namespace App\Models;

use App\Models\Traits\HasBook;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $audit_id
 * @property string $quantity
 */

class AuditInventory extends AppModel
{
    use \App\Models\Traits\Exports;

    public $table = 'audit_inventory';

    public $fillable = [
        'isbn',
        'quantity',
        'audit_id',
    ];

    /**
     * Not using the App\Models\Traits\HasBook trait because that comes with a lot of extra baggage.
     *
     * @return void
     */
    public function book(){
        return $this->belongsTo(Book::class, 'isbn', 'isbn');
    }
}
