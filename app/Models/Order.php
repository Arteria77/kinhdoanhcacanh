<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_fee',
        'to_district_id',
        'to_ward_code',
        'coupon_code',
        'discount_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
    ];

    // Một đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một đơn hàng có nhiều chi tiết sản phẩm
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}