<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = ['table_id', 'customer_name', 'customer_phone', 'party_size', 'start_time', 'end_time', 'status', 'special_requests'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function scopeOverlapping($query, $tableId, $startTime, $endTime)
    {
        return $query->where('table_id', $tableId)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sq) use ($startTime, $endTime) {
                    // Check if s_1 < e_2 AND e_1 > s_2
                    $sq->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->whereIn('status', ['pending', 'confirmed', 'seated']);
    }
}
