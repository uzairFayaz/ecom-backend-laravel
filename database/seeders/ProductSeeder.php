<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic products',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Smartphone',
            'description' => 'A high-end smartphone',
            'price' => 699.99,
            'stock_quantity' => 100,
            'status' => 'active',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'color' => 'SMARTPHONE-BLACK',
            'size' => 'Black',
            'additional_price' => 0.00,
            'stock_quantity' => 50,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'color' => 'SMARTPHONE-WHITE',
            'size' => 'White',
            'additional_price' => 10.00,
            'stock_quantity' => 30,
        ]);
    }
}
