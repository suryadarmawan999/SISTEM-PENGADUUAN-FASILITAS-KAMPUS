<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'facility_id',
        'title',
        'description',
        'campus',
        'location',
        'photo',
        'status',
        'admin_notes',
    ];

    /**
     * Get the user that owns the complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the facility that the complaint is about.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the tindak lanjut records for the complaint.
     */
    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(TindakLanjut::class);
    }
}
