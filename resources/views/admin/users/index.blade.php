@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="form-inline">
                            <input type="text" 
                                   name="search" 
                                   class="form-control mr-2" 
                                   placeholder="Cari nama atau email..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <div class="btn-group">
                                <form method="GET" class="form-inline">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <select name="role" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Role</option>
                                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary text-white mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-600">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'warning' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        {{ $user->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.users.toggle-status', $user) }}" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-sm" 
                                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($user->id !== auth()->id())
                                        <form method="POST" 
                                              action="{{ route('admin.users.destroy', $user) }}" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Tidak ada pengguna ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $users->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection