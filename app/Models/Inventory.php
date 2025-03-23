<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

/**
 * 
 * @property string $quantity
 * @property string $entity_type (po, fulfillment)
 * @property int    $entity_id
 * @property string $entity_label
 * @property string $note
 * @property int    $inventory_year
 * 
 * @property \App\Models\PurchaseOrder|\App\Models\Fulfillment $entity
 */

class Inventory extends AppModel
{

    public $table = 'inventory';

    use \App\Models\Traits\HasBook,
        \App\Models\Traits\Exports,
        \Illuminate\Database\Eloquent\SoftDeletes;

    protected $attributes = [
        'entity_id' => 1,
    ];

    public $fillable = [
        'isbn',
        'quantity',
        'fixed_value',
        'entity_id',
        'entity_type',
        'note',
        'inventory_year',
        'book_condition'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Inventory $inventory) {
            $inventory->user_id = auth()->id();
        });
    }

    public function entity()
    {
        switch ($this->entity_type) {
            case 'fulfillment':
                return $this->belongsTo(Fulfillment::class, 'entity_id');
            case 'po':
                return $this->belongsTo(PurchaseOrder::class, 'entity_id');
            default:
                throw new \Exception('`entity_type` "' . $this->entity_type . '" Not implemented');
        }
    }

    public function entityDisplay(): Attribute
    {

        return Attribute::make(
            get: fn () => $this->entity ? $this->entity->display : $this->entity_type . ':' . $this->entity_id,
        );
    }

    public function scopeByFulfillment(Builder $query, $fulfillment_id): Builder
    {
        return $query->where('entity_type', 'fulfillment')
            ->where('entity_id', $fulfillment_id);
    }

    public function scopeByPurchaseOrder(Builder $query, $po_id): Builder
    {
        return $query->where('entity_type', 'po')
            ->where('entity_id', $po_id);
    }
}
