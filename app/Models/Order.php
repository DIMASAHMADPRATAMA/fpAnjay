<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'kode_order',
        'name',
        'phone',
        'postal_code',
        'address',
        'courier',
        'total',
        'status',
        'product_summary',
        'total_items',
        'payment_status',       // ✅ tambahkan ini
        'shipping_status',      // ✅ dan ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
