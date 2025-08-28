<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllAsRead(Request $request)
    {
        foreach ($request->ids as $id) {
            $notification = Auth::user()->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }else {
                return response()->json(['message' => 'Notification not found'], 404);
            }
        }
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markAsRead(Request $request, $id)
    {
        // Get the notification by product_id and mark it as read
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

}
