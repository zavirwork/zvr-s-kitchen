@extends('user.master')
@section('page-title', 'Dashboard')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>Data diri anda</h6>
                    </div>
                    <div class="card-body">
                        @if(empty($profile) || request()->has('edit'))
                            <!-- Tampilkan form jika profile belum ada atau mode edit -->
                            @if(empty($profile))
                                <div class="alert alert-info">
                                    Silakan lengkapi data diri Anda terlebih dahulu
                                </div>
                            @endif
                            
                            <form action="{{ empty($profile) ? route('user.profile.store') : route('user.profile.update') }}" method="POST">
                                @csrf
                                @if(!empty($profile))
                                    @method('PUT')
                                @endif

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        value="{{ old('name', $profile->name ?? '') }}" placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" required
                                        value="{{ old('whatsapp', $profile->whatsapp ?? '') }}" placeholder="Contoh: 6281234567890">
                                    @error('whatsapp')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                <div class="d-flex justify-content-between">
                                    @if(!empty($profile))
                                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i> Batal
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        <i class="fas fa-save me-1"></i> Simpan Profil
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Tampilkan data jika profile sudah ada -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Lengkap</label>
                                        <input class="form-control" type="text" value="{{ $profile->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nomor WhatsApp</label>
                                        <input class="form-control" type="text" value="{{ $profile->whatsapp }}" disabled>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tombol edit -->
                            <div class="mt-3">
                                <a href="{{ route('user.dashboard') }}?edit=true" class="btn btn-warning">
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