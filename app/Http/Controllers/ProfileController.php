<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required',
            'user_id' => 'required',
        ]);

        // Find id 
        $user_id = Auth::user()->id;

        return redirect()->route('user.dashboard');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_whatsapp' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->user_whatsapp = $request->user_whatsapp;
        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
