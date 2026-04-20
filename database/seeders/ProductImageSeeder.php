<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $imageCount = 0;

        $availableCategoryImages = [
            'frozen',
            'beverage',
            'snacks',
            'fruits-vegetables',
            'pet-care',
            'household-cleaning-essentials',
        ];

        foreach ($products as $product) {
            if ($product->images()->exists()) {
                continue;
            }

            $categorySlug = Str::slug($product->category);
            if (!in_array($categorySlug, $availableCategoryImages, true)) {
                $categorySlug = 'snacks';
            }

            $filename = 'products/cat_' . $categorySlug . '.svg';

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
            ]);
            $imageCount++;
        }

        echo "Assigned local images to $imageCount products successfully!\n";
    }
}
