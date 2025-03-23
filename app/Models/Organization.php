<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $name
 * @property string $ein
 * @property string $note
 * @property int $primary_contact_id
 * @property int $organization_id
 * @property string display
 * @property \App\Models\Organization $organization
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Contact[] $contacts
 * @property \App\Models\Contact $primaryContact
 */
class Organization extends AppModel
{
    use \Orchid\Attachment\Attachable;
    use Traits\hasStared;

    protected $fillable = [
        'starred',
        'name',
        'organization_id',
        'ein',
        'note',
    ];

    protected $allowedSorts = [
        'name',
        'created_at',
        'updated_at',
    ];

    protected $allowedFilters = [
        'name' => \Orchid\Filters\Types\Like::class,
    ];

    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function primaryContact(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Contact::class, 'id', 'primary_contact_id');
    }

    public function scopeDefaultOrder(Builder $builder): Builder
    {
        return $builder->orderBy('starred', 'desc')->orderBy('name');
    }

    public function scopeHasContacts(Builder $builder): Builder
    {
        return $builder->has('contacts');
    }
}
