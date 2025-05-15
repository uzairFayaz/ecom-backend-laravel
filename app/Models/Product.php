<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_name',
        'description',
        'image',
        'price',
        'stock_quantity',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'string',
    ];
    public function cart(){
        return $this->hasMany(Cart::class,'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class ,'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class,'product_id');
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }


}
