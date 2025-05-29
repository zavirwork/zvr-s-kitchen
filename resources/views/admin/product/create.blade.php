@extends('admin.master')
@section('page-title', 'Products')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Add Product</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="food">Food</option>
                                        <option value="drink">Drink</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" class="form-control" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" name="stock" class="form-control" required>
                                </div>

                                <div class="col-12 mb-4">
                                    <label for="images" class="form-label">Image</label>
                                    <input type="file" name="images[]" class="form-control" multiple required>
                                    <small class="text-muted">You can select more than one image</small>
                                </div>

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
