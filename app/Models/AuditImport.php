<?php

namespace App\Models;

/**
 * @property string $note
 * @property int $audit_id
 * 
 * @property \App\Models\Audit $audit
 */
class AuditImport extends AppModel
{
    protected $fillable = [
        'note',
        'audit_id'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

}
