<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->get();

        return response()->json($notifications);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'is_read' => 'boolean',
        ]);

        $notification = Notification::create($data);

        return response()->json(['message' => 'Benachrichtigung erfolgreich erstellt.', 'notification' => $notification]);
    }

    public function markAsRead(Request $request)
    {
        $userId = Auth::id();

        $updatedRows = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        Notification::where('user_id', $userId)
            ->where('message', 'LIKE', 'Ihr Kontostand ist unter%')
            ->where('is_read', true)
            ->delete();

        return response()->json(['message' => "$updatedRows Benachrichtigungen als gelesen markiert und gelöscht."]);
    }
}
