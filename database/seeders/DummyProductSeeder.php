<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class DummyProductSeeder extends Seeder
{
    public function run()
    {
        $seller = User::where('role', 'seller')->first();
        if (!$seller) {
            // Fallback if no seller exists
            $seller = User::create([
                'name' => 'Default Seller',
                'email' => 'seller_default@freshmart.com',
                'password' => bcrypt('password123'),
                'role' => 'seller'
            ]);
        }

        $catalog = [
            'Frozen' => [
                'Premium Frozen Pizza', 'Frozen Mixed Vegetables', 'Vanilla Ice Cream Tub', 'Frozen Fish Fillets', 'Frozen Chicken Nuggets',
                'Frozen French Fries', 'Frozen Berries Blend', 'Frozen Edamame', 'Frozen Sweet Corn', 'Frozen Waffles'
            ],
            'Beverage' => [
                'Fresh Brewed Iced Coffee', 'Sparkling Water 6-Pack', 'Orange Juice 1L', 'Green Tea Extracts', 'Cola Soda Pack',
                'Energy Drink', 'Organic Coconut Water', 'Unsweetened Almond Milk', 'Apple Cider', 'Bottled Mineral Water'
            ],
            'Snacks' => [
                'Classic Potato Chips', 'Roasted Mixed Nuts', 'Cheddar Cheese Crackers', 'Dark Chocolate Bar', 'Organic Gummy Fruit',
                'Microwave Popcorn', 'Salted Pretzels', 'Spicy Tortilla Chips', 'Honey Granola Bars', 'Light Rice Cakes'
            ],
            'Fruits & Vegetables' => [
                'Organic Cavendish Bananas', 'Fresh Fuji Apples', 'Crisp Baby Carrots', 'Red Onions (1kg)', 'Broccoli Crown',
                'Fresh Spinach Bunch', 'Ripe Hass Avocados', 'Sweet Potatoes', 'Vine Tomatoes', 'Green Bell Peppers'
            ],
            'Pet Care' => [
                'Premium Dry Dog Food', 'Canned Tuna Cat Food', 'Odor Control Cat Litter', 'Durable Dog Chew Toys', 'Gentle Pet Shampoo',
                'Mixed Bird Seed Bag', 'Guinea Pig Pellet Mix', 'Chicken Dog Treats', 'Organic Catnip', 'Aquarium Filter Gravel'
            ],
            'Household Cleaning & Essentials' => [
                'All-Purpose Surface Cleaner', 'Lemon Dishwashing Liquid', 'Heavy Duty Laundry Detergent', 'Absorbent Paper Towels', 'Soft Toilet Paper',
                'Streak-Free Glass Cleaner', 'Scrub Sponges Pack', 'Concentrated Bleach Bottle', 'Heavy Duty Trash Bags', 'Citrus Disinfectant Wipes'
            ]
        ];

        $count = 0;
        foreach ($catalog as $category => $items) {
            foreach ($items as $item) {
                // Skip if already exists to avoid duplicates if run multiple times
                if (Product::where('name', $item)->exists()) {
                    continue;
                }

                Product::create([
                    'user_id' => $seller->id,
                    'name' => $item,
                    'category' => $category,
                    'description' => "High quality " . strtolower($item) . " for your everyday grocery needs. Sourced fresh and guaranteed to satisfy.",
                    'price' => mt_rand(30, 450) + (mt_rand(0, 99) / 100),
                    'stock' => mt_rand(15, 120),
                ]);
                $count++;
            }
        }
        
        echo "Seeded $count new products successfully!\n";
    }
}
