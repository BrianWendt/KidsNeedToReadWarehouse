<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $note
 * @property int $organization_id
 * @property string $received_date
 * @property int $contact_id
 * @property int $address_id
 * @property int $telephone_id
 * @property int $email_id
 * @property string $display
 * @property float $total_amount
 * @property string $book_condition
 * @property \App\Models\Organization $organization
 * @property \App\Models\Contact $contact
 * @property \App\Models\Address $address
 * @property \App\Models\Telephone $telephone
 * @property \App\Models\Email $email
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\FulfillmentInventory[] $inventory
 */
class PurchaseOrder extends AppModel
{
    use \Laravel\Scout\Searchable;
    use \Orchid\Filters\Filterable;

    protected $fillable = [
        'organization_id',
        'received_date',
        'contact_id',
        'address_id',
        'telephone_id',
        'email_id',
        'note',
        'book_condition',
    ];

    protected $allowedSorts = [
        'contact.name',
        'created_at',
        'updated_at',
    ];

    protected $allowedFilters = [
        'id' => \Orchid\Filters\Types\Where::class,
        'contact_id' => \Orchid\Filters\Types\Where::class,
        'organization_id' => \Orchid\Filters\Types\Where::class,
        'created_at' => \Orchid\Filters\Types\WhereDateStartEnd::class,
    ];

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function address()
    {
        return $this->belongsTo(\App\Models\Address::class);
    }

    public function telephone()
    {
        return $this->belongsTo(\App\Models\Telephone::class);
    }

    public function email()
    {
        return $this->belongsTo(\App\Models\Email::class);
    }

    public function inventory()
    {
        return $this->hasMany(\App\Models\Inventory::class, 'entity_id')->where('entity_type', 'po');
    }

    public function display(): Attribute
    {
        return Attribute::make(
            get: fn () => 'PO #' . $this->id,
        );
    }

    public function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->inventory->sum(function ($item) {
                return $item->book ? ($item->price * $item->quantity) : 0;
            }),
        );
    }

    public function books(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->inventory->sum(function ($item) {
                return $item->book ? $item->quantity : 0;
            }),
        );
    }

    public function nonBooks(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->inventory->sum(function ($item) {
                return $item->book ? 0 : $item->quantity;
            }),
        );
    }

    public function viewRoute(): string
    {
        return 'app.purchase_order.view';
    }

    /**
     * Get the presenter for the model.
     *
     * @return \App\Orchid\Presenters\PurchaseOrderPresenter
     */
    public function presenter()
    {
        return new \App\Orchid\Presenters\PurchaseOrderPresenter($this);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = [
            'id' => $this->id,
            'note' => $this->note,
            'organization_id' => $this->organization_id,
        ];

        // Customize the data array...

        return $array;
    }
}
