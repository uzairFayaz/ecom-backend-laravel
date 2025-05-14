<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'parent_cat_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_cat_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_cat_id')->with('children','products');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'category_id')->where('status','active');
    }
}
