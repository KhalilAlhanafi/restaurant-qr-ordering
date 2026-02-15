<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkout extends Model
{
    protected $fillable = ['order_id', 'payment_method', 'amount_paid', 'change_amount', 'tip_amount', 'status', 'transaction_reference', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
