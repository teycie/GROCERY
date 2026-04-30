<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryAssignment;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    public function buyerPurchases()
    {
        $purchases = Delivery::where('user_id', Auth::id())
            ->with(['product.images', 'rider'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('buyer.purchases.index', compact('purchases'));
    }

    public function buyerPurchaseDetails(Delivery $delivery)
    {
        if ((int) $delivery->user_id !== (int) Auth::id()) {
            abort(403, 'You are not allowed to view this purchase.');
        }

        $delivery->load(['product.images', 'rider']);

        return view('buyer.purchases.show', [
            'purchase' => $delivery,
        ]);
    }

    public function index()
    {
        $buyersSummary = Delivery::with('user:id,name,username')
            ->selectRaw('user_id, count(*) as checkout_count, sum(quantity) as total_products')
            ->groupBy('user_id')
            ->orderByDesc('checkout_count')
            ->get();
        
        $deliveries = Delivery::query()
            ->with(['product', 'user', 'rider'])
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
            ->whereIn('tracking_status', ['shipped', 'ready', 'ready_to_pickup', 'rider_assigned', 'picked_up', 'on_delivery'])
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
            ->with(['product', 'user', 'rider'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($deliveries->isEmpty()) {
            abort(404, 'No checkout records found for this buyer.');
        }

        $totalCheckouts = Delivery::where('user_id', $buyer->id)
            ->count();

        $totalProducts = Delivery::where('user_id', $buyer->id)
            ->sum('quantity');

        // Available riders for assignment
        $availableRiders = User::where('role', 'rider')->get();

        return view('seller.deliveries.buyer-details', compact(
            'buyer',
            'deliveries',
            'totalCheckouts',
            'totalProducts',
            'availableRiders'
        ));
    }

    /**
     * Assign a rider to a delivery.
     */
    public function assignRider(Request $request, Delivery $delivery)
    {
        if (!in_array(Auth::user()->role, ['seller', 'admin'], true)) {
            abort(403, 'You are not allowed to assign riders.');
        }

        $validated = $request->validate([
            'rider_id' => ['required', 'exists:users,id'],
        ]);

        $rider = User::where('id', $validated['rider_id'])
            ->where('role', 'rider')
            ->firstOrFail();

        // Update delivery with rider info
        $delivery->update([
            'rider_id' => $rider->id,
            'tracking_status' => 'rider_assigned',
            'status' => 'processing',
            'rider_assigned_at' => now(),
        ]);

        // Create assignment record
        DeliveryAssignment::create([
            'delivery_id' => $delivery->id,
            'rider_id' => $rider->id,
            'assigned_by' => Auth::id(),
            'status' => 'assigned',
        ]);

        // Notify the rider
        $rider->notify(new OrderStatusNotification(
            $delivery,
            'New delivery assigned! Order #' . $delivery->order_id . ' — ' . optional($delivery->product)->name . ' to ' . optional($delivery->user)->name . '.',
            'rider_assigned'
        ));

        // Notify the buyer
        $delivery->user->notify(new OrderStatusNotification(
            $delivery,
            'A rider has been assigned to your order #' . $delivery->order_id . '. Your package will be picked up soon!',
            'rider_assigned'
        ));

        return back()->with('success', 'Rider ' . $rider->name . ' has been assigned to order #' . $delivery->order_id . '.');
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

        // Send notification to buyer for key status changes
        $notificationMessages = [
            'approved' => 'Your order #' . $delivery->order_id . ' has been approved by the seller!',
            'preparing' => 'Your order #' . $delivery->order_id . ' is being prepared.',
            'delivered' => 'Your order #' . $delivery->order_id . ' has been delivered!',
            'cancelled' => 'Your order #' . $delivery->order_id . ' has been cancelled.',
        ];

        if (isset($notificationMessages[$status])) {
            $delivery->user->notify(new OrderStatusNotification(
                $delivery,
                $notificationMessages[$status],
                $status
            ));
        }

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
            'preparing' => 'processing',
            'rider_assigned' => 'processing',
            'picked_up' => 'shipped',
            'on_delivery' => 'out_for_delivery',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
        ];

        return $deliveryMap[$trackingStatus] ?? 'pending';
    }
}
