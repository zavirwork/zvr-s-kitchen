@extends('admin.master')
@section('page-title', 'Edit Product')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Edit Product</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update.products', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="food" {{ $product->type === 'food' ? 'selected' : '' }}>Food</option>
                                        <option value="drink" {{ $product->type === 'drink' ? 'selected' : '' }}>Drink</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label">Image (Upload new if replacing)</label>
                                    <input type="file" name="images[]" class="form-control" multiple>
                                    <small class="text-muted">You can select more than one image</small>
                                </div>

                                @if ($product->images->isNotEmpty())
                                    <div class="col-12 mb-4">
                                        <label class="form-label">Current Images:</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($product->images as $img)
                                                <img src="{{ asset('storage/' . $img->path) }}" width="80" class="img-thumbnail">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <button type="button" class="btn btn-danger" onclick="window.history.back()">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
