<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacilityCategory extends Model
{
    protected $fillable = [
        'category_name',
    ];

    /**
     * Get the facilities for the category.
     */
    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class, 'category_id');
    }
}
