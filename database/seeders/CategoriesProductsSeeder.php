<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = DB::table('categories')->insertGetId([
            'category_name' => 'Electronics',
           // 'url_slug' => Str::slug('Electronics'),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = DB::table('products')->insertGetId([
            'product_name' => 'Smartphone',
           // 'url_slug' => Str::slug('Smartphone'),
            'category_id' => $categoryId,
            'description' => 'A high-end smartphone.',
            'price' => 599.99,
            'stock_quantity' => 100,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('product_variants')->insert([
            'product_id' => $productId,
            'color' => 'Black',
            'size' => '128GB',
            'price' => 599.99,
            'stock_quantity' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
