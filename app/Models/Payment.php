<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',
        'status',
        'amount',
        'provider_ref',
        'payment_data'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'succeeded' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800'
        ];

        return $statuses[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
