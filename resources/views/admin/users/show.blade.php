@extends('layouts.app')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Informasi Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl bg-primary text-white mx-auto mb-3">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="mb-2">
                        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }} badge-lg">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div>
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'warning' }} badge-lg">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="mt-2">
                            <h6 class="text-muted">Total Langganan</h6>
                            <h4 class="text-primary">{{ $user->subscriptions->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mt-2">
                            <h6 class="text-muted">Total Invoice</h6>
                            <h4 class="text-success">{{ $user->invoices->count() }}</h4>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted mb-1">Bergabung</p>
                        <p>{{ $user->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted mb-1">Terakhir Update</p>
                        <p>{{ $user->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Pengguna
                    </a>
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $user->is_active ? 'secondary' : 'success' }} btn-block">
                            <i class="fas fa-{{ $user->is_active ? 'times' : 'check' }}"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Pengguna
                        </button>
                    </form>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Riwayat Langganan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Durasi</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->subscriptions as $subscription)
                            <tr>
                                <td>
                                    <div class="font-weight-600">{{ $subscription->package->name ?? 'Paket Dihapus' }}</div>
                                </td>
                                <td>
                                    @if($subscription->package)
                                    <span class="badge badge-info">
                                        {{ $subscription->package->duration }} 
                                        {{ $subscription->package->duration_type ?? 'hari' }}
                                    </span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-success">
                                        Rp {{ number_format($subscription->package->price ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($subscription->status) {
                                            'active' => 'success',
                                            'expired' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'warning'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusColor }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        {{ $subscription->created_at->format('d M Y') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <p>Belum ada langganan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Riwayat Invoice</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->invoices as $invoice)
                            <tr>
                                <td>
                                    <div class="font-weight-600">#{{ $invoice->invoice_number }}</div>
                                </td>
                                <td>
                                    <div class="text-success">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($invoice->status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusColor }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        {{ $invoice->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                    <p>Belum ada invoice</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection