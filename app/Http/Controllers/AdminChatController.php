<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminChatController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $adminId = Auth::id();

        $products = Product::with('category')->get();
        $users = User::where('role', 'user')->get();
        $unreadByUser = Message::where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->groupBy('sender_id')
            ->selectRaw('sender_id, COUNT(*) as total')
            ->pluck('total', 'sender_id');

        return view('admin.dashboard', compact('products', 'users', 'unreadByUser'));
    }

    // Halaman Chat
    public function chat($id)
    {
        $adminId = Auth::id();
        $user = User::findOrFail($id);

        // Tandai pesan dari user sebagai dibaca
        Message::where('sender_id', $id)
            ->where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $this->getMessages($adminId, $id);
        $users = User::where('role', 'user')->get();
        $unreadCount = Message::where('receiver_id', $adminId)->whereNull('read_at')->count();
        $unreadByUser = Message::where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->groupBy('sender_id')
            ->selectRaw('sender_id, COUNT(*) as total')
            ->pluck('total', 'sender_id');

        return view('admin.chat', compact('user', 'messages', 'users', 'unreadCount', 'unreadByUser'));
    }

    // Endpoint polling (hanya bagian pesan chat)
    public function refreshChat($id)
    {
        $adminId = Auth::id();
        $messages = $this->getMessages($adminId, $id);

        return view('admin.partials.chat-messages', compact('messages'))->render();
    }

    // Kirim Pesan
    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $id,
            'message'     => $request->message,
            'read_at'     => null
        ]);

        return redirect()->route('admin.chat', $id)->with('success', 'Pesan berhasil dikirim');
    }

    // Ambil pesan antara admin dan user
    private function getMessages($adminId, $userId)
    {
        return Message::where(function ($q) use ($adminId, $userId) {
                $q->where('sender_id', $adminId)
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($adminId, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $adminId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
