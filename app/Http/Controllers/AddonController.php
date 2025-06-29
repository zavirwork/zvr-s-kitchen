<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addons = Addon::all();
        return view('admin.addon.index', compact('addons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Addon::create($request->only('name', 'price'));
        return redirect()->route('admin.addons.index')->with('success', 'Addon successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Addon $addon)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        
        $addon->update($request->only('name', 'price'));
        return redirect()->route('admin.addons.index')->with('success', 'Add-on diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Addon $addon)
    {
        $addon->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on dihapus.');
    }
}
