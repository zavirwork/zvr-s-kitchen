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
        $profile = Profile::create([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'user_id' => $user_id,
        ]);

        return redirect()->route('user.dashboard');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required',
        ]);

        $profile = Profile::where('user_id', auth()->id())->first();
        $profile->update($request->only(['name', 'whatsapp']));

        return redirect()->route('user.dashboard');
    }
}
