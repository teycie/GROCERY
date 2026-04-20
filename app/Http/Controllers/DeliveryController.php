<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();
        
        $deliveries = Delivery::where('seller_id', $sellerId)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Delivery stats
        $pendingDeliveries = Delivery::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->count();
            
        $processingDeliveries = Delivery::where('seller_id', $sellerId)
            ->where('status', 'processing')
            ->count();
            
        $shippedDeliveries = Delivery::where('seller_id', $sellerId)
            ->where('status', 'shipped')
            ->count();
            
        $deliveredDeliveries = Delivery::where('seller_id', $sellerId)
            ->where('status', 'delivered')
            ->count();

        // Status breakdown
        $statusBreakdown = Delivery::where('seller_id', $sellerId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('seller.deliveries.index', compact(
            'deliveries',
            'pendingDeliveries',
            'processingDeliveries',
            'shippedDeliveries',
            'deliveredDeliveries',
            'statusBreakdown'
        ));
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,out_for_delivery,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $delivery->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $delivery->notes,
            'delivered_date' => $request->status === 'delivered' ? now()->toDateString() : $delivery->delivered_date,
        ]);

        return back()->with('success', 'Delivery status updated successfully.');
    }
}
