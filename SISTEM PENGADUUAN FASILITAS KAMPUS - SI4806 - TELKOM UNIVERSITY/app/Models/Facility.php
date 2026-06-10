<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = [
        'facility_name',
        'category_id',
        'description',
        'location',
        'status',
    ];

    /**
     * Get the category that owns the facility.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FacilityCategory::class, 'category_id');
    }

    /**
     * Get the complaints for the facility.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
