<?php $__env->startSection('title', 'Detail Pengguna - ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Informasi Pengguna</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl bg-primary text-white mx-auto mb-3">
                        <?php echo e(substr($user->name, 0, 2)); ?>

                    </div>
                    <h5><?php echo e($user->name); ?></h5>
                    <p class="text-muted"><?php echo e($user->email); ?></p>
                    <div class="mb-2">
                        <span class="badge badge-<?php echo e($user->role === 'admin' ? 'danger' : 'primary'); ?> badge-lg">
                            <?php echo e(ucfirst($user->role)); ?>

                        </span>
                    </div>
                    <div>
                        <span class="badge badge-<?php echo e($user->is_active ? 'success' : 'warning'); ?> badge-lg">
                            <?php echo e($user->is_active ? 'Aktif' : 'Nonaktif'); ?>

                        </span>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="mt-2">
                            <h6 class="text-muted">Total Langganan</h6>
                            <h4 class="text-primary"><?php echo e($user->subscriptions->count()); ?></h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mt-2">
                            <h6 class="text-muted">Total Invoice</h6>
                            <h4 class="text-success"><?php echo e($user->invoices->count()); ?></h4>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted mb-1">Bergabung</p>
                        <p><?php echo e($user->created_at->format('d F Y H:i')); ?></p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted mb-1">Terakhir Update</p>
                        <p><?php echo e($user->updated_at->format('d F Y H:i')); ?></p>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Pengguna
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-<?php echo e($user->is_active ? 'secondary' : 'success'); ?> btn-block">
                            <i class="fas fa-<?php echo e($user->is_active ? 'times' : 'check'); ?>"></i>
                            <?php echo e($user->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?> Pengguna
                        </button>
                    </form>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-primary">
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
                            <?php $__empty_1 = true; $__currentLoopData = $user->subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="font-weight-600"><?php echo e($subscription->package->name ?? 'Paket Dihapus'); ?></div>
                                </td>
                                <td>
                                    <?php if($subscription->package): ?>
                                    <span class="badge badge-info">
                                        <?php echo e($subscription->package->duration); ?> 
                                        <?php echo e($subscription->package->duration_type ?? 'hari'); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-success">
                                        Rp <?php echo e(number_format($subscription->package->price ?? 0, 0, ',', '.')); ?>

                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $statusColor = match($subscription->status) {
                                            'active' => 'success',
                                            'expired' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'warning'
                                        };
                                    ?>
                                    <span class="badge badge-<?php echo e($statusColor); ?>">
                                        <?php echo e(ucfirst($subscription->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <?php echo e($subscription->created_at->format('d M Y')); ?>

                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <p>Belum ada langganan</p>
                                </td>
                            </tr>
                            <?php endif; ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $user->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="font-weight-600">#<?php echo e($invoice->invoice_number); ?></div>
                                </td>
                                <td>
                                    <div class="text-success">
                                        Rp <?php echo e(number_format($invoice->amount, 0, ',', '.')); ?>

                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $statusColor = match($invoice->status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'info'
                                        };
                                    ?>
                                    <span class="badge badge-<?php echo e($statusColor); ?>">
                                        <?php echo e(ucfirst($invoice->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <?php echo e($invoice->created_at->format('d M Y H:i')); ?>

                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                    <p>Belum ada invoice</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/admin/users/show.blade.php ENDPATH**/ ?>