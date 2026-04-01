<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $imageCount = 0;

        foreach ($products as $product) {
            $product->images()->delete();

            $filename = 'products/cat_' . Str::slug($product->category) . '.svg';

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
            ]);
            $imageCount++;
        }

        echo "Assigned local images to $imageCount products successfully!\n";
    }
}
