<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $contact_id
 * @property string $display
 * @property string $name
 * @property string $street1
 * @property string $street2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property \App\Models\Contact $contact
 */
class Address extends AppModel
{
    use \Orchid\Filters\Filterable;

    protected $fillable = [
        'contact_id',
        'name',
        'street1',
        'street2',
        'city',
        'state',
        'zipcode',
    ];

    protected $allowedSorts = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function display(): Attribute
    {
        $address = $this->street1 . "\n";
        if ($this->street2) {
            $address .= $this->street2 . "\n";
        }
        $address .= $this->city . ', ' . $this->state . ' ' . $this->zipcode;

        return Attribute::make(
            get: fn () => $address,
        );
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
