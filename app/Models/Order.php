<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['table_id', 'status', 'total_amount', 'special_requests', 'estimated_minutes', 'is_checked_out', 'admin_seen_at', 'notes', 'customer_name', 'customer_phone', 'completed_at'];

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function scopeActiveForTable($query, $tableId)
    {
        return $query->where('table_id', $tableId)
            ->where('is_checked_out', false)
            ->whereIn('status', ['pending', 'confirmed', 'preparing']);
    }

    public function checkout(): HasOne
    {
        return $this->hasOne(Checkout::class, 'order_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'order_tax')
            ->withPivot('tax_amount')
            ->withTimestamps();
    }

    public function hasUnseenUpdates(): bool
    {
        return $this->unseenItemsCount() > 0;
    }

    public function unseenItemsCount(): int
    {
        return $this->orderItems()
            ->whereNull('admin_seen_at')
            ->count();
    }

    public function unseenItems(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->orderItems()
            ->whereNull('admin_seen_at')
            ->get();
    }

    public function markAsSeen(): void
    {
        $this->orderItems()
            ->whereNull('admin_seen_at')
            ->update(['admin_seen_at' => now()]);
        // Touch the order to trigger polling updates
        $this->touch();
    }

    public function markItemAsSeen(int $itemId): void
    {
        $this->orderItems()
            ->where('id', $itemId)
            ->whereNull('admin_seen_at')
            ->update(['admin_seen_at' => now()]);
        // Touch the order to trigger polling updates
        $this->touch();
    }
}
