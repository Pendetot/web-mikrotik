<?php $__env->startSection('title', 'Kelola Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
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
                                    <?php echo e(number_format($stats['total_invoices'] ?? 0)); ?>

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
                                    <?php echo e(number_format($stats['paid_invoices'] ?? 0)); ?>

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
                                    <?php echo e(number_format($stats['pending_invoices'] ?? 0)); ?>

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
                                    Rp <?php echo e(number_format($stats['total_revenue'] ?? 0, 0, ',', '.')); ?>

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
                                   value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <div class="btn-group">
                                <form method="GET" class="form-inline">
                                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                    <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="paid" <?php echo e(request('status') === 'paid' ? 'selected' : ''); ?>>Lunas</option>
                                        <option value="failed" <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>Gagal</option>
                                        <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Dibatalkan</option>
                                    </select>
                                    <select name="payment_method" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Metode</option>
                                        <option value="bank_transfer" <?php echo e(request('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="credit_card" <?php echo e(request('payment_method') === 'credit_card' ? 'selected' : ''); ?>>Credit Card</option>
                                        <option value="e_wallet" <?php echo e(request('payment_method') === 'e_wallet' ? 'selected' : ''); ?>>E-Wallet</option>
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
                            <?php $__empty_1 = true; $__currentLoopData = $invoices ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="font-weight-600"><?php echo e($invoice->invoice_number); ?></div>
                                    <div class="text-muted small"><?php echo e($invoice->created_at->format('d M Y H:i')); ?></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary text-white mr-2">
                                            <?php echo e(substr($invoice->user->name ?? 'N', 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-weight-600"><?php echo e($invoice->user->name ?? 'N/A'); ?></div>
                                            <div class="text-muted small"><?php echo e($invoice->user->email ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600"><?php echo e($invoice->package->name ?? 'N/A'); ?></div>
                                    <div class="text-muted small"><?php echo e($invoice->package->duration ?? 'N/A'); ?> hari</div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-success">
                                        Rp <?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?>

                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $paymentMethod = $invoice->payments->first()?->payment_method ?? 'N/A';
                                        $methodText = [
                                            'bank_transfer' => 'Bank Transfer',
                                            'credit_card' => 'Credit Card',
                                            'e_wallet' => 'E-Wallet'
                                        ][$paymentMethod] ?? ucfirst(str_replace('_', ' ', $paymentMethod));
                                    ?>
                                    <span class="badge badge-info"><?php echo e($methodText); ?></span>
                                </td>
                                <td>
                                    <?php
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
                                    ?>
                                    <span class="badge badge-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                                    <?php if($invoice->due_date && $invoice->status === 'pending' && $invoice->due_date < now()): ?>
                                    <br><small class="text-danger">Overdue</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-muted small"><?php echo e($invoice->created_at->format('d M Y')); ?></div>
                                    <?php if($invoice->paid_at): ?>
                                    <div class="text-muted small">Paid: <?php echo e($invoice->paid_at->format('d M Y')); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-info btn-sm" 
                                                data-show-url="<?php echo e(route('admin.invoices.show', $invoice)); ?>"
                                                onclick="viewInvoice(this)"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if($invoice->status === 'pending'): ?>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                onclick="updateStatus(<?php echo e($invoice->id); ?>, 'paid')"
                                                title="Tandai Lunas">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="updateStatus(<?php echo e($invoice->id); ?>, 'failed')"
                                                title="Tandai Gagal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                    <p>Tidak ada data invoice ditemukan</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(isset($invoices) && $invoices->hasPages()): ?>
                <div class="d-flex justify-content-center">
                    <?php echo e($invoices->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
    
    const updateUrl = "<?php echo e(route('admin.invoices.update-status', ':id')); ?>".replace(':id', currentInvoiceId);
    
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/admin/payments/index.blade.php ENDPATH**/ ?>