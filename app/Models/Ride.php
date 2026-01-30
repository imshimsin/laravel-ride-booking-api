<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ride extends Model
{
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'pickup_latitude',
        'pickup_longitude',
        'destination_latitude',
        'destination_longitude',
        'status',
        'passenger_completed_at',
        'driver_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'passenger_completed_at' => 'datetime',
            'driver_completed_at' => 'datetime',
        ];
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function driverRequests(): HasMany
    {
        return $this->hasMany(RideDriverRequest::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
