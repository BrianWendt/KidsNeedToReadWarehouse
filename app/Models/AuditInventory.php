<?php

namespace App\Models;

/**
 * @property string $audit_id
 * @property string $quantity
 * @property string $book_condition
 * @property \App\Models\Audit $audit
 * @property \App\Models\Book $book
 */
class AuditInventory extends AppModel
{
    use \App\Models\Traits\Exports;

    public $table = 'audit_inventory';

    public $fillable = [
        'isbn',
        'quantity',
        'book_condition',
        'audit_id',
    ];

    /**
     * Not using the App\Models\Traits\HasBook trait because that comes with a lot of extra baggage.
     *
     * @return void
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'isbn', 'isbn');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }
}
