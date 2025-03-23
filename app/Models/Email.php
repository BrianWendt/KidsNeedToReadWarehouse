<?php

namespace App\Models;

/**
 * @property int $contact_id
 * @property string $name
 * @property string $address
 * @property string $note
 * @property \App\Models\Contact $contact
 */
class Email extends AppModel
{
    protected $fillable = [
        'contact_id',
        'name',
        'address',
        'note'
    ];

    public function contact() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
