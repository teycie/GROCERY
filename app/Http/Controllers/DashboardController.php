<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function guestDashboard()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        try {
            $products = Product::with('user')
                ->whereHas('user', function ($userQuery) {
                    $userQuery->whereIn('role', ['seller', 'admin']);
                })
                ->latest()
                ->take(8)
                ->get();
            $announcements = Announcement::latest()->take(3)->get();
        } catch (QueryException $exception) {
            // Allow local development without a running DB service.
            $products = collect();
            $announcements = collect();
        }

        return view('dashboard.guest', compact('products', 'announcements'));
    }

    /**
     * Generic dashboard route that redirects by user role.
     */
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'seller' || $role === 'admin') {
            return redirect()->route('seller.dashboard');
        }

        if ($role === 'rider') {
            return redirect()->route('rider.dashboard');
        }

        return redirect()->route('buyer.dashboard');
    }

    public function buyerDashboard()
    {
        $search = trim((string) request('search', ''));

        $productsQuery = Product::with('user')
            ->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role', ['seller', 'admin']);
            });

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        $products = $productsQuery
            ->latest()
            ->take(6)
            ->get();
        $announcements = Announcement::latest()->take(5)->get();

        $recentPurchases = Delivery::where('user_id', Auth::id())
            ->with(['product', 'seller'])
            ->latest()
            ->take(2)
            ->get();
        
        // Analytics
        $totalProductsAvailable = Product::count();
        $cart = Auth::user()->cart;
        $totalPurchases = Delivery::where('user_id', Auth::id())->count();
        $pendingPurchases = Delivery::where('user_id', Auth::id())
            ->where('tracking_status', 'pending')
            ->count();
        
        $cartItemsCount = 0;
        $cartTotalValue = 0;
        
        if ($cart) {
            $cartItemsCount = $cart->items()->sum('quantity');
            $cartTotalValue = DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.cart_id', $cart->id)
                ->sum(DB::raw('cart_items.quantity * products.price'));
        }

        return view('dashboard.buyer', compact(
            'products',
            'announcements',
            'totalProductsAvailable',
            'cartItemsCount',
            'cartTotalValue',
            'recentPurchases',
            'totalPurchases',
            'pendingPurchases',
            'search'
        ));
    }

    public function sellerDashboard()
    {
        $currentUser = Auth::user();
        $sellerIds = $currentUser->role === 'admin'
            ? User::whereIn('role', ['seller', 'admin'])->pluck('id')
            : collect([$currentUser->id]);

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

        $recentCheckouts = Delivery::with(['product', 'user'])
            ->latest()
            ->take(2)
            ->get();

        $totalCheckoutOrders = Delivery::count();
        $pendingCheckoutOrders = Delivery::whereIn('status', ['pending', 'processing'])
            ->count();

        return view('dashboard.seller', compact(
            'productsCount',
            'recentProducts',
            'announcements',
            'totalBuyers',
            'totalCartItems',
            'potentialRevenue',
            'chartLabels',
            'chartValues',
            'recentCheckouts',
            'totalCheckoutOrders',
            'pendingCheckoutOrders'
        ));
    }
}
