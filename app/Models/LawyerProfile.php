<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LawyerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bar_registration_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}