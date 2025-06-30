<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Ambil semua pesan antara user login dan user lain
    public function index(Request $request)
    {
        $userId = Auth::id();
        $partnerId = $request->query('partner_id');

        $messages = Message::where(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $userId)->where('receiver_id', $partnerId);
            })
            ->orWhere(function ($q) use ($userId, $partnerId) {
                $q->where('sender_id', $partnerId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Kirim pesan
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }

    // Cek jumlah pesan yang belum dibaca oleh user login
    public function checkUnread($userId)
    {
        if (Auth::id() !== (int) $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $count = Message::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json(['unread' => $count]);
    }

    // Tandai semua pesan dari partner sebagai sudah dibaca
    public function markAsRead(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:users,id'
        ]);

        $userId = Auth::id();
        $partnerId = $request->partner_id;

        Message::where('sender_id', $partnerId)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}
