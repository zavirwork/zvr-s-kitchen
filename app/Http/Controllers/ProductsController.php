<?php

namespace App\Http\Controllers;

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
        return view('admin.product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,drink',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array|max:5',
        ]);

        $product = Products::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                ProductImage::create([
                    'products_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('index.products')->with('success', 'Product successfully added!');
    }

    public function edit(Products $product)
    {
        $product->load('images'); // untuk ambil semua gambar
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, Products $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:food,drink',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Cek apakah ada gambar baru yang diupload
        if ($request->hasFile('images')) {
            // Hapus semua gambar lama dari storage dan database
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path); // Hapus file fisik
                $image->delete(); // Hapus dari database
            }

            // Simpan gambar baru
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'products_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('index.products')->with('success', 'Product successfully updated!');
    }

    public function destroy(Products $product)
    {
        $product->delete();
        return redirect()->route('index.products')->with('success', 'Product successfully deleted!');
    }
}
