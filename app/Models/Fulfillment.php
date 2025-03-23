<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $display
 * @property int $status
 * @property string $status_display
 * @property int $program_id
 * @property int $children_served
 * @property int $contact_id
 * @property int $shipping_contact_id
 * @property int $shipping_address_id
 * @property string $description
 * @property string $tracking
 * @property int $organization_id
 * @property \App\Models\Organization $organization
 * @property \App\Models\Contact $contact
 * @property \App\Models\Contact $shipping_contact
 * @property \App\Models\Address $shipping_address
 * @property \App\Models\Program $program
 * @property \App\Models\Initiative $initiative
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\FulfillmentInventory[] $inventory
 */

class Fulfillment extends AppModel {

    protected $fillable = [
        'status',
        'program_id',
        'initiative_id',
        'children_served',
        'contact_id',
        'shipping_contact_id',
        'shipping_address_id',
        'tracking',
        'organization_id',
        'description',
    ];

    protected $attributes = [
        'status' => 'new'
    ];

    protected $allowedSorts = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $allowedFilters = [
        'id' => \Orchid\Filters\Types\Where::class,
        //'status' => \Orchid\Filters\Types\Where::class,
    ];

    public function display() : Attribute
    {
        return Attribute::make(
            get: fn() => "Fulfillment #{$this->id} ({$this->status_display})",
        );
    }

    public function statusDisplay() : Attribute
    {
        return Attribute::make(
            get: fn() => config('options.fulfillment_statuses')[$this->status] ?? $this->status,
        );
    }

    public function organization() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function contact() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function shipping_contact() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class, 'shipping_contact_id');
    }

    public function shipping_address() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }

    public function program() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function initiative() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Initiative::class);
    }

    public function inventory() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FulfillmentInventory::class);
    }

    public function viewRoute() : string
    {
        return 'app.fulfillment.view';
    }
}