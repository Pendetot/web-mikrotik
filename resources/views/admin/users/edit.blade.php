@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Pengguna</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select id="role" 
                                        name="role" 
                                        class="form-control @error('role') is-invalid @enderror" 
                                        required>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                        User
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select id="is_active" 
                                        name="is_active" 
                                        class="form-control @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                                @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3">Ubah Password (Opsional)</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Kosongkan jika tidak ingin mengubah">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-control"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Bergabung</p>
                                <p class="font-weight-600">{{ $user->created_at->format('d F Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Terakhir Update</p>
                                <p class="font-weight-600">{{ $user->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#role').change(function() {
        if ($(this).val() === 'admin') {
            if (!confirm('Yakin ingin mengubah role menjadi Admin? User akan mendapat akses penuh ke sistem.')) {
                $(this).val('user');
            }
        }
    });
});
</script>
@endpush