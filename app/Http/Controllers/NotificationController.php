<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(25);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $deliveryId = $notification->data['delivery_id'] ?? null;
        if ($deliveryId) {
            if ($user->role === 'buyer') {
                return redirect()->route('buyer.purchases.show', $deliveryId);
            } elseif (in_array($user->role, ['seller', 'admin'])) {
                return redirect()->route('seller.deliveries.index'); // Could link to buyer-details if buyer ID was available
            } elseif ($user->role === 'rider') {
                return redirect()->route('rider.deliveries.show', $deliveryId);
            }
        }

        return redirect()->back();
    }

    public function markAllRead()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $n) {
            $n->markAsRead();
        }
        return redirect()->back();
    }

    /**
     * Poll endpoint for real-time notification updates (JSON).
     */
    public function poll()
    {
        $user = Auth::user();
        $unreadCount = $user->unreadNotifications()->count();
        $latest = $user->unreadNotifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'message' => data_get($n->data, 'message', 'New notification'),
                    'status' => data_get($n->data, 'status', ''),
                    'order_id' => data_get($n->data, 'order_id', ''),
                    'time' => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $latest,
        ]);
    }
}
