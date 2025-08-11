<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'subtotal',
        'delivery_fee',
        'discount',
        'total'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'ready' => 'bg-green-100 text-green-800',
            'awaiting_delivery_pickup' => 'bg-purple-100 text-purple-800',
            'out_for_delivery' => 'bg-orange-100 text-orange-800',
            'delivered' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-red-100 text-red-800'
        ];

        return $statuses[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusDisplayAttribute()
    {
        $displays = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'ready' => 'Ready',
            'awaiting_delivery_pickup' => 'Awaiting Delivery Pickup',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];

        return $displays[$this->status] ?? ucfirst($this->status);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canProceedToDelivery(): bool
    {
        return in_array($this->status, ['ready', 'processing']);
    }

    public function canBePickedUp(): bool
    {
        return $this->status === 'awaiting_delivery_pickup';
    }

    public function canBeDelivered(): bool
    {
        return $this->status === 'out_for_delivery';
    }

    public function getItemsSummaryAttribute()
    {
        return $this->items->map(function($item) {
            return $item->product->name . ' x' . $item->qty;
        })->join(', ');
    }
}
