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
}
