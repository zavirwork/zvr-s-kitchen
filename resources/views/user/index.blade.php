@extends('user.master')
@section('page-title', 'Dashboard')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Data Diri Anda</h6>
                    </div>
                    <div class="card-body">
                        @if(request()->has('edit'))
                            <!-- Form Edit Profil -->
                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama lengkap">
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="user_whatsapp" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" class="form-control" id="user_whatsapp" name="user_whatsapp" required
                                           value="{{ old('user_whatsapp', auth()->user()->user_whatsapp) }}"
                                           placeholder="Contoh: 6281234567890">
                                    @error('user_whatsapp')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Tampilkan Data -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Lengkap</label>
                                        <input class="form-control" type="text" value="{{ auth()->user()->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nomor WhatsApp</label>
                                        <input class="form-control" type="text" value="{{ auth()->user()->user_whatsapp }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('user.dashboard', ['edit' => 'true']) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit Profil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
