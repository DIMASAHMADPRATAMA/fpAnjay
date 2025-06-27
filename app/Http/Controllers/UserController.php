<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 📌 Get Profile User yang Login
    public function profile()
    {
        return response()->json(Auth::user());
    }

    // ✏️ Update Profile
    public function update(Request $request)
{
    $user = Auth::user();

    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($user->id),
        ],
    ]);

    // Update manual tanpa update()
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json([
        'message' => 'Profil diperbarui',
        'user' => $user
    ]);
}
}
