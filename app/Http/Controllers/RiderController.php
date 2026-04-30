<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryAssignment;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    /**
     * Rider dashboard — shows summary stats and active deliveries.
     */
    public function dashboard()
    {
        $rider = Auth::user();

        $activeDeliveries = Delivery::where('rider_id', $rider->id)
            ->whereNotIn('tracking_status', ['delivered', 'cancelled'])
            ->with(['product.images', 'user', 'seller'])
            ->orderBy('rider_assigned_at', 'desc')
            ->get();

        $totalDelivered = Delivery::where('rider_id', $rider->id)
            ->where('tracking_status', 'delivered')
            ->count();

        $totalAssigned = Delivery::where('rider_id', $rider->id)->count();

        $onDeliveryCount = Delivery::where('rider_id', $rider->id)
            ->where('tracking_status', 'on_delivery')
            ->count();

        $pickedUpCount = Delivery::where('rider_id', $rider->id)
            ->where('tracking_status', 'picked_up')
            ->count();

        return view('rider.dashboard', compact(
            'activeDeliveries',
            'totalDelivered',
            'totalAssigned',
            'onDeliveryCount',
            'pickedUpCount'
        ));
    }

    /**
     * All deliveries assigned to this rider (active + history).
     */
    public function deliveries(Request $request)
    {
        $rider = Auth::user();
        $filter = $request->query('filter', 'active');

        $query = Delivery::where('rider_id', $rider->id)
            ->with(['product.images', 'user', 'seller']);

        if ($filter === 'active') {
            $query->whereNotIn('tracking_status', ['delivered', 'cancelled']);
        } elseif ($filter === 'history') {
            $query->whereIn('tracking_status', ['delivered', 'cancelled']);
        }

        $deliveries = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('rider.deliveries', compact('deliveries', 'filter'));
    }

    /**
     * Show single delivery details for the rider.
     */
    public function showDelivery(Delivery $delivery)
    {
        if ((int) $delivery->rider_id !== (int) Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        $delivery->load(['product.images', 'user', 'seller', 'assignments']);

        return view('rider.delivery-detail', compact('delivery'));
    }

    /**
     * Mark delivery as picked up.
     */
    public function pickUp(Delivery $delivery)
    {
        if ((int) $delivery->rider_id !== (int) Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        if (!in_array($delivery->tracking_status, ['rider_assigned'], true)) {
            return back()->withErrors(['status' => 'This delivery cannot be picked up in its current state.']);
        }

        $delivery->update([
            'tracking_status' => 'picked_up',
            'status' => 'processing',
            'picked_up_at' => now(),
        ]);

        // Update assignment record
        $assignment = DeliveryAssignment::where('delivery_id', $delivery->id)
            ->where('rider_id', Auth::id())
            ->latest()
            ->first();
            
        if ($assignment) {
            $assignment->update(['status' => 'picked_up']);
        }

        // Notify buyer
        $delivery->user->notify(new OrderStatusNotification(
            $delivery,
            'Your order #' . $delivery->order_id . ' has been picked up by the rider.',
            'picked_up'
        ));

        return back()->with('success', 'Order marked as picked up.');
    }

    /**
     * Mark delivery as on the way.
     */
    public function onTheWay(Delivery $delivery)
    {
        if ((int) $delivery->rider_id !== (int) Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        if (!in_array($delivery->tracking_status, ['picked_up'], true)) {
            return back()->withErrors(['status' => 'This delivery must be picked up first.']);
        }

        $delivery->update([
            'tracking_status' => 'on_delivery',
            'status' => 'out_for_delivery',
            'on_delivery_at' => now(),
        ]);

        // Update assignment record
        $assignment = DeliveryAssignment::where('delivery_id', $delivery->id)
            ->where('rider_id', Auth::id())
            ->latest()
            ->first();
            
        if ($assignment) {
            $assignment->update(['status' => 'on_delivery']);
        }

        // Notify buyer
        $delivery->user->notify(new OrderStatusNotification(
            $delivery,
            'Your order #' . $delivery->order_id . ' is on the way!',
            'on_delivery'
        ));

        return back()->with('success', 'Order marked as on the way.');
    }

    /**
     * Mark delivery as delivered.
     */
    public function delivered(Delivery $delivery)
    {
        if ((int) $delivery->rider_id !== (int) Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        if (!in_array($delivery->tracking_status, ['on_delivery'], true)) {
            return back()->withErrors(['status' => 'This delivery must be on the way first.']);
        }

        $delivery->update([
            'tracking_status' => 'delivered',
            'status' => 'delivered',
            'delivered_date' => now()->toDateString(),
        ]);

        // Update assignment record
        $assignment = DeliveryAssignment::where('delivery_id', $delivery->id)
            ->where('rider_id', Auth::id())
            ->latest()
            ->first();
            
        if ($assignment) {
            $assignment->update(['status' => 'delivered']);
        }

        // Notify buyer
        $delivery->user->notify(new OrderStatusNotification(
            $delivery,
            'Your order #' . $delivery->order_id . ' has been delivered! Thank you for shopping with FreshMart.',
            'delivered'
        ));

        return back()->with('success', 'Order marked as delivered. Great job!');
    }
}
