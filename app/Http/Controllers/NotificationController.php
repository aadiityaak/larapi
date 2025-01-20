<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications;
        return response()->json($notifications);
    }

    public function update($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        $notification->markAsRead();

        return response()->json($notification);
    }
}
