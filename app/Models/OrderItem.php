<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    // Chi tiết đơn hàng thuộc về một đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Chi tiết đơn hàng liên kết với một sản phẩm cá cảnh
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}