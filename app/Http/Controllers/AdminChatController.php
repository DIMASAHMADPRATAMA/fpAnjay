<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    // Menampilkan halaman chat antara admin dan user
    public function chat($id)
    {
        $user = User::findOrFail($id); // User yang sedang di-chat
        $adminId = Auth::id();

        // Ambil semua pesan antara admin dan user
        $messages = Message::where(function ($q) use ($adminId, $id) {
                $q->where('sender_id', $adminId)
                  ->where('receiver_id', $id);
            })->orWhere(function ($q) use ($adminId, $id) {
                $q->where('sender_id', $id)
                  ->where('receiver_id', $adminId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chat', compact('user', 'messages'));
    }

    // Kirim pesan dari admin ke user
    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id'   => Auth::id(), // admin
            'receiver_id' => $id,        // user
            'message'     => $request->message
        ]);

        return redirect()->route('admin.chat', $id)->with('success', 'Pesan berhasil dikirim');
    }
}
