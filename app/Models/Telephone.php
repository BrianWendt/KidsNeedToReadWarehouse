<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $contact_id
 * @property string $display
 * @property string $name
 * @property string $number
 * @property string $extension
 * @property string $note
 * @property \App\Models\Contact $contact
 */

class Telephone extends AppModel
{
    use \Orchid\Filters\Filterable;

    protected $fillable = [
        'name',
        'contact_id',
        'number',
        'extension',
        'note'
    ];

    public function contact() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function display() : Attribute
    {
        $display = $this->number;
        if ($this->extension) {
            $display .= ' x' . $this->extension;
        }
        return Attribute::make(
            get: fn () => $display,
        );
    }
}
