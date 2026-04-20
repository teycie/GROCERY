<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $productsQuery = Product::whereHas('user', function ($query) {
                $query->whereIn('role', ['seller', 'admin']);
            })
            ->with(['images', 'user']);

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

        return view('seller.inventory.index', compact(
            'products',
            'totalProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalInventoryValue'
        ));
    }
}
