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
 * @property string $book_condition 
 * 
 * @property \App\Models\Organization $organization 
 * @property \App\Models\Contact $contact
 * @property \App\Models\Address $address
 * @property \App\Models\Telephone $telephone
 * @property \App\Models\Email $email
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\FulfillmentInventory[] $inventory
 */

class PurchaseOrder extends AppModel
{
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
        'updated_at'
    ];

    public function organization() : \Illuminate\Database\Eloquent\Relations\BelongsTo
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

    public function display() : Attribute
    {
        return Attribute::make(
            get: fn() => 'PO #' . $this->id,
        );
    }

    public function viewRoute() : string
    {
        return 'app.purchase_order.view';
    }
}
