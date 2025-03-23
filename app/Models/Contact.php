<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string $preferred_name
 * @property string $display
 * @property string $display_name
 * @property string $title
 * @property string $note
 * @property int $organization_id
 * @property int $primary_address_id
 * @property int $primary_telephone_id
 * @property int $primary_email_id
 * @property \App\Models\Organization $organization
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Telephone[] $telephones
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Email[] $emails
 */
class Contact extends AppModel
{
    protected $fillable = [
        'first_name',
        'last_name',
        'preferred_name',
        'title',
        'ein',
        'note',
        'organization_id',
        'primary_address_id',
        'primary_telephone_id',
        'primary_email_id',
    ];

    protected $allowedSorts = [
        'first_name',
        'last_name',
        'created_at',
        'updated_at',
    ];

    protected $allowedFilters = [
        'first_name' => \Orchid\Filters\Types\Like::class,
        'last_name' => \Orchid\Filters\Types\Like::class,
        'organization_id' => \Orchid\Filters\Types\Where::class,
    ];

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function telephones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Telephone::class);
    }

    public function emails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Email::class);
    }

    public function primaryAddress(): Attribute
    {
        if (! empty($this->primary_address_id)) {
            $address = $this->addresses->find($this->primary_address_id);
        } else {
            $address = $this->addresses->first();
        }

        return Attribute::make(
            get: fn () => $address,
        );
    }

    public function primaryTelephone(): Attribute
    {
        if (! empty($this->primary_telephone_id)) {
            $telephone = $this->telephones->find($this->primary_telephone_id);
        } else {
            $telephone = $this->telephones->first();
        }

        return Attribute::make(
            get: fn () => $telephone,
        );
    }

    public function primaryEmail(): Attribute
    {
        if (! empty($this->primary_email_id)) {
            $email = $this->emails->find($this->primary_email_id);
        } else {
            $email = $this->emails->first();
        }

        return Attribute::make(
            get: fn () => $email,
        );
    }

    /**
     * Laravel scope to filter contacts by organization
     */
    public function scopeOrganizationId(Builder $builder, $organization_id): Builder
    {
        return $builder->where('organization_id', $organization_id);
    }

    public function scopeFullName(Builder $builder): Builder
    {
        return $builder->addSelect(DB::raw('*, CONCAT(first_name, " ", last_name) as full_name'));
    }

    public function scopeJoinOrganization(Builder $builder): Builder
    {
        return $builder->join('organizations', 'contacts.organization_id', '=', 'organizations.id');
    }

    public function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->preferred_name ?? $this->full_name,
        );
    }

    public function fullName(): Attribute
    {
        $full_name = $this->first_name.' '.$this->last_name;

        return Attribute::make(
            get: fn () => $full_name,
        );
    }

    public function display(): Attribute
    {
        $display = $this->display_name;

        if ($this->title) {
            $display .= ', '.$this->title;
        }
        if ($this->organization) {
            $display .= ' @ '.$this->organization->name;
        }

        return Attribute::make(
            get: fn () => $display,
        );
    }
}
