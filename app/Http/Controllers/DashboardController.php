<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function guestDashboard()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $products = Product::latest()->take(8)->get();
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
        $products = Product::latest()->take(6)->get();
        $announcements = Announcement::latest()->take(5)->get();

        return view('dashboard.buyer', compact('products', 'announcements'));
    }

    public function sellerDashboard()
    {
        $productsCount = Auth::user()->products()->count();
        $announcements = Announcement::latest()->take(5)->get();

        return view('dashboard.seller', compact('productsCount', 'announcements'));
    }
}
