<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $created_at_display
 * @property string $updated_at_display
 * @property string $created_at_date
 * @property string $updated_at_date
 */
trait HasTimestamps
{
    private function dateFormattedAttribute($date, $format)
    {
        $return = null;
        if ($date) {
            try {
                $return = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format($format);
            } catch (\Exception $e) {
                $return = null;
            }
        }

        return Attribute::make(
            get: fn () => $return,
        );
    }

    public function createdAtDisplay(): Attribute
    {
        return $this->dateFormattedAttribute($this->created_at, 'Y-m-d g:i A');
    }

    public function updatedAtDisplay(): Attribute
    {
        return $this->dateFormattedAttribute($this->updated_at, 'Y-m-d g:i A');
    }

    public function createdAtDate(): Attribute
    {
        return $this->dateFormattedAttribute($this->created_at, 'Y-m-d');
    }

    public function updatedAtDate(): Attribute
    {
        return $this->dateFormattedAttribute($this->updated_at, 'Y-m-d');
    }
}
