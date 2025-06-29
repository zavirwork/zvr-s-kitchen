@extends('admin.master')
@section('page-title', 'Add-ons')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Add-ons Table</h6>
                        <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addAddonModal">
                            <i class="fas fa-plus"></i> Add Add-on
                        </a>

                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            No</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Price</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder text-center align-middle">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($addons as $addon)
                                        <tr>
                                            <td class="text-center align-middle text-sm">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle text-sm">{{ $addon->name }}</td>
                                            <td class="text-center align-middle text-sm">Rp
                                                {{ number_format($addon->price, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#editAddonModal{{ $addon->id }}">
                                                    Edit
                                                </a>

                                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteAction('{{ route('admin.addons.destroy', $addon->id) }}')">Delete</a>    
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editAddonModal{{ $addon->id }}" tabindex="-1"
                                            aria-labelledby="editAddonModalLabel{{ $addon->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('admin.addons.update', $addon->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Add-on</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="mb-3">
                                                                <label class="form-label">Add-on Name</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ $addon->name }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Price (Rp)</label>
                                                                <input type="number" step="0.01" name="price"
                                                                    class="form-control" value="{{ $addon->price }}"
                                                                    required>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-dark">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No add-ons found.</td>
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

    <!-- Modal Tambah Add-on -->
    <div class="modal fade" id="addAddonModal" tabindex="-1" aria-labelledby="addAddonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.addons.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddonModalLabel">Add New Add-on</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="addon-name" class="form-label">Add-on Name</label>
                            <input type="text" name="name" class="form-control" id="addon-name" required>
                        </div>

                        <div class="mb-3">
                            <label for="addon-price" class="form-label">Price (Rp)</label>
                            <input type="number" name="price" class="form-control" id="addon-price" step="0.01"
                                required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                </div>
            </form>
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
