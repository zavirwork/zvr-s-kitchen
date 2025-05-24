@extends('admin.master')
@section('page-title', 'Products')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Products Table</h6>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-dark">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Stock</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Image</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td class="text-center align-middle text-sm">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle text-sm">{{ $product->name }}</td>
                                            <td class="text-center align-middle text-sm">{{ ucfirst($product->type) }}</td>
                                            <td class="text-center align-middle text-sm">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="text-center align-middle text-sm">{{ $product->stock }}</td>
                                            <td class="text-center align-middle text-sm">
                                                @if ($product->images->isNotEmpty())
                                                    <img src="{{ asset('storage/' . $product->images[0]->path) }}"
                                                        alt="product-img" width="40">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.products.edit', $product->id)}}" class="btn btn-sm btn-info">Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteAction('{{ route('admin.products.destroy', $product->id) }}')">Delete</a>                                               
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    const notyf = new Notyf({
        duration: 4000,
        position: {
            x: 'right',
            y: 'top',
        }
    });

    @if (session('success'))
        notyf.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        notyf.error("{{ session('error') }}");
    @endif
</script>
@endsection