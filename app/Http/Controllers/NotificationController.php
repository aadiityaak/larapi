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

    public function read($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        $notification->markAsRead();

        return response()->json($notification);
    }

    public function readAll()
    {
        $notifications = Auth::user()->notifications()->get();
        $notifications->each(function ($notification) {
            $notification->markAsRead();
        });

        return response()->json($notifications);
    }
    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications->map(function ($data) {
            return [

                'id' => $data->id,
                'message' => $data->data['message'],
                'time' => $data->created_at
            ];
        });

        return response()->json($notifications);
    }
}
