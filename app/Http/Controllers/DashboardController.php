<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function guestDashboard()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $products = Product::with('user')
            ->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role', ['seller', 'admin']);
            })
            ->latest()
            ->take(8)
            ->get();
        $announcements = Announcement::latest()->take(3)->get();

        return view('dashboard.guest', compact('products', 'announcements'));
    }

    /**
     * Generic dashboard route that redirects by user role.
     */
    public function index()
    {
        if (Auth::user()->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('buyer.dashboard');
    }

    public function buyerDashboard()
    {
        $products = Product::with('user')
            ->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role', ['seller', 'admin']);
            })
            ->latest()
            ->take(6)
            ->get();
        $announcements = Announcement::latest()->take(5)->get();
        
        // Analytics
        $totalProductsAvailable = Product::count();
        $cart = Auth::user()->cart;
        
        $cartItemsCount = 0;
        $cartTotalValue = 0;
        
        if ($cart) {
            $cartItemsCount = $cart->items()->sum('quantity');
            $cartTotalValue = DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.cart_id', $cart->id)
                ->sum(DB::raw('cart_items.quantity * products.price'));
        }

        return view('dashboard.buyer', compact('products', 'announcements', 'totalProductsAvailable', 'cartItemsCount', 'cartTotalValue'));
    }

    public function sellerDashboard()
    {
        $sellerIds = User::whereIn('role', ['seller', 'admin'])->pluck('id');
        $productsQuery = Product::whereIn('user_id', $sellerIds)->with('images');
        $productsCount = $productsQuery->count();
        $recentProducts = (clone $productsQuery)->latest()->take(6)->get();
        $announcements = Announcement::latest()->take(5)->get();
        
        // Analytics
        $totalBuyers = User::where('role', 'buyer')->count();
        
        $totalCartItems = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->whereIn('products.user_id', $sellerIds)
            ->sum('cart_items.quantity');
            
        $potentialRevenue = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->whereIn('products.user_id', $sellerIds)
            ->sum(DB::raw('cart_items.quantity * products.price'));

        // Chart Data: Products by Category
        $categoryData = Product::whereIn('user_id', $sellerIds)
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
            
        $chartLabels = array_keys($categoryData);
        $chartValues = array_values($categoryData);

        return view('dashboard.seller', compact(
            'productsCount', 'recentProducts', 'announcements', 'totalBuyers', 'totalCartItems', 'potentialRevenue', 'chartLabels', 'chartValues'
        ));
    }
}
