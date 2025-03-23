<?php

namespace App\Models;

/**
 * @property string $label
 * @property \Carbon\Carbon $closed_at
 * @property AuditInventory[]|\Illuminate\Database\Eloquent\Collection $audit_inventory
 */
class Audit extends AppModel
{
    protected $fillable = [
        'label',
    ];

    public function audit_inventory()
    {
        return $this->hasMany(AuditInventory::class);
    }
}
