@extends('layouts.app')

@section('title', 'Kelola Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Pembayaran</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Invoice</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($stats['total_invoices'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Invoice Lunas</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($stats['paid_invoices'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Pending</h4>
                                </div>
                                <div class="card-body">
                                    {{ number_format($stats['pending_invoices'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Revenue</h4>
                                </div>
                                <div class="card-body">
                                    Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="form-inline">
                            <input type="text" 
                                   name="search" 
                                   class="form-control mr-2" 
                                   placeholder="Cari invoice atau user..." 
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
                                    <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <select name="payment_method" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Metode</option>
                                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
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
                                <th>Invoice</th>
                                <th>User</th>
                                <th>Paket</th>
                                <th>Amount</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices ?? [] as $invoice)
                            <tr>
                                <td>
                                    <div class="font-weight-600">{{ $invoice->invoice_number }}</div>
                                    <div class="text-muted small">{{ $invoice->created_at->format('d M Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary text-white mr-2">
                                            {{ substr($invoice->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-600">{{ $invoice->user->name ?? 'N/A' }}</div>
                                            <div class="text-muted small">{{ $invoice->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600">{{ $invoice->package->name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $invoice->package->duration ?? 'N/A' }} hari</div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-success">
                                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $paymentMethod = $invoice->payments->first()?->payment_method ?? 'N/A';
                                        $methodText = [
                                            'bank_transfer' => 'Bank Transfer',
                                            'credit_card' => 'Credit Card',
                                            'e_wallet' => 'E-Wallet'
                                        ][$paymentMethod] ?? ucfirst(str_replace('_', ' ', $paymentMethod));
                                    @endphp
                                    <span class="badge badge-info">{{ $methodText }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary',
                                            'refunded' => 'info'
                                        ][$invoice->status] ?? 'secondary';
                                        
                                        $statusText = [
                                            'pending' => 'Pending',
                                            'paid' => 'Lunas',
                                            'failed' => 'Gagal',
                                            'cancelled' => 'Batal',
                                            'refunded' => 'Refund'
                                        ][$invoice->status] ?? ucfirst($invoice->status);
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                                    @if($invoice->due_date && $invoice->status === 'pending' && $invoice->due_date < now())
                                    <br><small class="text-danger">Overdue</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-muted small">{{ $invoice->created_at->format('d M Y') }}</div>
                                    @if($invoice->paid_at)
                                    <div class="text-muted small">Paid: {{ $invoice->paid_at->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-info btn-sm" 
                                                data-show-url="{{ route('admin.invoices.show', $invoice) }}"
                                                onclick="viewInvoice(this)"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($invoice->status === 'pending')
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                onclick="updateStatus({{ $invoice->id }}, 'paid')"
                                                title="Tandai Lunas">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="updateStatus({{ $invoice->id }}, 'failed')"
                                                title="Tandai Gagal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                    <p>Tidak ada data invoice ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($invoices) && $invoices->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $invoices->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="paid">Lunas</option>
                            <option value="failed">Gagal</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="form-group" id="notesGroup" style="display: none;">
                        <label for="notes">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentInvoiceId = null;

function viewInvoice(button) {
    const showUrl = button.getAttribute('data-show-url');
    if (showUrl) {
        window.open(showUrl, '_blank');
    } else {
        alert('URL tidak ditemukan');
    }
}

function updateStatus(id, status) {
    currentInvoiceId = id;
    
    const statusSelect = document.getElementById('status');
    const notesGroup = document.getElementById('notesGroup');
    
    statusSelect.value = status;
    
    if (status === 'failed' || status === 'cancelled') {
        notesGroup.style.display = 'block';
    } else {
        notesGroup.style.display = 'none';
    }
    
    $('#statusModal').modal('show');
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const status = formData.get('status');
    const notes = formData.get('notes');
    
    if (!status) {
        alert('Pilih status terlebih dahulu');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    const updateUrl = "{{ route('admin.invoices.update-status', ':id') }}".replace(':id', currentInvoiceId);
    
    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#statusModal').modal('hide');
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengupdate status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

$('#statusModal').on('hidden.bs.modal', function () {
    document.getElementById('statusForm').reset();
    document.getElementById('notesGroup').style.display = 'none';
    currentInvoiceId = null;
});

document.getElementById('status').addEventListener('change', function() {
    const notesGroup = document.getElementById('notesGroup');
    if (this.value === 'failed' || this.value === 'cancelled') {
        notesGroup.style.display = 'block';
    } else {
        notesGroup.style.display = 'none';
    }
});
</script>
@endpush