<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'value',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_tax')
            ->withPivot('tax_amount')
            ->withTimestamps();
    }

    public function calculateTax($subtotal)
    {
        if ($this->type === 'percentage') {
            return $subtotal * ($this->value / 100);
        }
        
        return $this->value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
