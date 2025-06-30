<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\ProductImage;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::with('images')->latest()->get();
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $addons = Addon::all();
        return view('admin.product.create', compact('addons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,drink',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'required|array|max:5',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id'
        ]);

        $product = Products::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Simpan relasi add-on (jika ada)
        if ($request->filled('addon_ids')) {
            $product->addons()->sync($request->addon_ids);
        }

        // Simpan gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                ProductImage::create([
                    'products_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product successfully added!');
    }


    public function edit(Products $product)
    {
        $product->load(['images', 'addons']);
        $addons = Addon::all();
        $productAddonIds = $product->addons->pluck('id')->toArray();

        return view('admin.product.edit', compact('product', 'addons', 'productAddonIds'));
    }


    public function update(Request $request, Products $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,drink',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id'
        ]);

        $product->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Update relasi add-on
        $product->addons()->sync($request->addon_ids ?? []);

        // Cek dan update gambar
        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'products_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product successfully updated!');
    }


    public function destroy(Products $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product successfully deleted!');
    }

    public function show($id)
    {
        $product = Products::with('images')->findOrFail($id);
        return view('visitors.detail.index', compact('product'));
    }
}
