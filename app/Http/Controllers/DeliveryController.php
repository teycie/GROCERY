<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    public function index()
    {
        $buyersSummary = Delivery::with('user:id,name,username')
            ->selectRaw('user_id, count(*) as checkout_count, sum(quantity) as total_products')
            ->groupBy('user_id')
            ->orderByDesc('checkout_count')
            ->get();
        
        $deliveries = Delivery::query()
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Delivery stats
        $pendingDeliveries = Delivery::query()
            ->where('tracking_status', 'pending')
            ->count();
            
        $processingDeliveries = Delivery::query()
            ->whereIn('tracking_status', ['approved', 'processing', 'preparing'])
            ->count();
            
        $shippedDeliveries = Delivery::query()
            ->whereIn('tracking_status', ['shipped', 'ready', 'ready_to_pickup'])
            ->count();
            
        $deliveredDeliveries = Delivery::query()
            ->whereIn('tracking_status', ['delivered', 'picked_up'])
            ->count();

        // Status breakdown
        $statusBreakdown = Delivery::query()
            ->selectRaw('tracking_status, count(*) as count')
            ->groupBy('tracking_status')
            ->pluck('count', 'tracking_status')
            ->toArray();

        return view('seller.deliveries.index', compact(
            'buyersSummary',
            'deliveries',
            'pendingDeliveries',
            'processingDeliveries',
            'shippedDeliveries',
            'deliveredDeliveries',
            'statusBreakdown'
        ));
    }

    public function buyerDetails(User $buyer)
    {
        $deliveries = Delivery::where('user_id', $buyer->id)
            ->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($deliveries->isEmpty()) {
            abort(404, 'No checkout records found for this buyer.');
        }

        $totalCheckouts = Delivery::where('user_id', $buyer->id)
            ->count();

        $totalProducts = Delivery::where('user_id', $buyer->id)
            ->sum('quantity');

        return view('seller.deliveries.buyer-details', compact(
            'buyer',
            'deliveries',
            'totalCheckouts',
            'totalProducts'
        ));
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        if (!in_array(Auth::user()->role, ['seller', 'admin'], true)) {
            abort(403, 'You are not allowed to update this order.');
        }

        $availableStatuses = Delivery::statusesFor($delivery->fulfillment_type);

        $validated = $request->validate([
            'status' => ['required', Rule::in($availableStatuses)],
            'notes' => 'nullable|string|max:500',
        ]);

        $status = $validated['status'];
        $legacyStatus = $this->mapLegacyStatus($status, $delivery->fulfillment_type);

        $delivery->update([
            'tracking_status' => $status,
            'status' => $legacyStatus,
            'notes' => $validated['notes'] ?? $delivery->notes,
            'delivered_date' => in_array($status, ['delivered', 'picked_up'], true) ? now()->toDateString() : $delivery->delivered_date,
        ]);

        return back()->with('success', 'Delivery status updated successfully.');
    }

    private function mapLegacyStatus(string $trackingStatus, ?string $fulfillmentType): string
    {
        if ($fulfillmentType === 'pickup') {
            $pickupMap = [
                'pending' => 'pending',
                'approved' => 'processing',
                'preparing' => 'processing',
                'ready' => 'shipped',
                'ready_to_pickup' => 'out_for_delivery',
                'picked_up' => 'delivered',
                'cancelled' => 'cancelled',
            ];

            return $pickupMap[$trackingStatus] ?? 'pending';
        }

        $deliveryMap = [
            'pending' => 'pending',
            'approved' => 'processing',
            'processing' => 'processing',
            'shipped' => 'shipped',
            'out_for_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
        ];

        return $deliveryMap[$trackingStatus] ?? 'pending';
    }
}
