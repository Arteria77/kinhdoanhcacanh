<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'category_id',
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        'name',
        'price',
        'stock',
        'description',
        'image',
    ];

<<<<<<< HEAD
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}