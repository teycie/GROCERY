<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    private $categories = [
        'Frozen',
        'Beverage',
        'Snacks',
        'Fruits & Vegetables',
        'Pet Care',
        'Household Cleaning & Essentials',
    ];

    public function index(\Illuminate\Http\Request $request)
    {
        $productsQuery = Product::whereHas('user', function ($query) {
                $query->whereIn('role', ['seller', 'admin']);
            })
            ->with(['images', 'user']);

        // Apply category filter if provided
        if ($request->filled('category')) {
            $productsQuery->where('category', $request->category);
        }

        $products = (clone $productsQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate inventory stats
        $totalProducts = (clone $productsQuery)->count();
        $lowStockProducts = (clone $productsQuery)
            ->where('stock', '<', 5)
            ->count();
        $outOfStockProducts = (clone $productsQuery)
            ->where('stock', '<=', 0)
            ->count();

        $totalInventoryValue = (clone $productsQuery)
            ->sum(\Illuminate\Support\Facades\DB::raw('stock * price'));

        $selectedCategory = $request->category;

        return view('seller.inventory.index', compact(
            'products',
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalInventoryValue',
            'categories',
            'selectedCategory'
        ));
    }
}
