<?php

namespace App\Models;

use App\Models\Traits\HasBook;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $isbn
 * @property string $fulfillment_id
 * @property string $quantity
 * @property string $note
 * 
 * @property string $price
 * 
 * @property \App\Models\Fulfillment $fulfillment
 */

class FulfillmentInventory extends AppModel
{
    use HasBook,
    \App\Models\Traits\Exports;

    public $table = 'fulfillment_inventory';

    public $fillable = [
        'isbn',
        'quantity',
        'note',
        'book_condition',
        'fixed_value',
    ];

    public function fulfillment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Fulfillment::class);
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price * $this->quantity,
        );
    }

}
