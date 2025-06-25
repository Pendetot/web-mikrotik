<?php $__env->startSection('title', 'Kelola Paket Langganan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Paket Langganan</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="form-inline">
                            <input type="text" 
                                   name="search" 
                                   class="form-control mr-2" 
                                   placeholder="Cari nama paket..." 
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
                                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Aktif</option>
                                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Nonaktif</option>
                                    </select>
                                    <select name="sort" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="newest" <?php echo e(request('sort') === 'newest' ? 'selected' : ''); ?>>Terbaru</option>
                                        <option value="oldest" <?php echo e(request('sort') === 'oldest' ? 'selected' : ''); ?>>Terlama</option>
                                        <option value="price_low" <?php echo e(request('sort') === 'price_low' ? 'selected' : ''); ?>>Harga Terendah</option>
                                        <option value="price_high" <?php echo e(request('sort') === 'price_high' ? 'selected' : ''); ?>>Harga Tertinggi</option>
                                        <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>Nama A-Z</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Paket</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo e($stats['total_packages'] ?? 0); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Paket Aktif</h4>
                                </div>
                                <div class="card-body">
                                    <?php echo e($stats['active_packages'] ?? 0); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <a href="<?php echo e(route('admin.packages.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Paket
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Durasi</th>
                                <th>Harga</th>
                                <th>Subscribers</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary text-white mr-3">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-600"><?php echo e($package->name); ?></div>
                                            <div class="text-muted small"><?php echo e($package->code ?? 'PKG-' . $package->id); ?></div>
                                            <?php if($package->description): ?>
                                            <div class="text-muted small text-truncate" style="max-width: 200px;" title="<?php echo e($package->description); ?>">
                                                <?php echo e($package->description); ?>

                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo e($package->duration); ?> <?php echo e($package->duration_type ?? 'hari'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-success">
                                        Rp <?php echo e(number_format($package->price, 0, ',', '.')); ?>

                                    </div>
                                    <?php if($package->original_price && $package->original_price > $package->price): ?>
                                    <div class="text-muted small">
                                        <s>Rp <?php echo e(number_format($package->original_price, 0, ',', '.')); ?></s>
                                        <span class="text-danger">
                                            (<?php echo e(round((($package->original_price - $package->price) / $package->original_price) * 100)); ?>% OFF)
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="font-weight-600"><?php echo e($package->subscriptions_count ?? 0); ?></div>
                                    <div class="text-muted small">subscribers</div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo e($package->is_active ? 'success' : 'danger'); ?>">
                                        <?php echo e($package->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                    </span>
                                    <?php if($package->featured): ?>
                                    <br><span class="badge badge-warning mt-1">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <?php echo e($package->created_at->format('d M Y')); ?>

                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">                                                                   <a class="dropdown-item" href="<?php echo e(route('admin.packages.edit', $package)); ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form method="POST" action="<?php echo e(route('admin.packages.toggle-status', $package)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-<?php echo e($package->is_active ? 'times' : 'check'); ?>"></i>
                                                    <?php echo e($package->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('admin.packages.toggle-featured', $package)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-<?php echo e($package->featured ? 'star-o' : 'star'); ?>"></i>
                                                    <?php echo e($package->featured ? 'Unfeatured' : 'Set Featured'); ?>

                                                </button>
                                            </form>
                                            <div class="dropdown-divider"></div>
                                            <form method="POST" action="<?php echo e(route('admin.packages.destroy', $package)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Yakin ingin menghapus paket ini? Semua data langganan terkait akan terpengaruh.')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-box fa-3x mb-3"></i>
                                    <p>Belum ada paket langganan</p>
                                    <a href="<?php echo e(route('admin.packages.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Paket Pertama
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($packages->hasPages()): ?>
                <div class="d-flex justify-content-center">
                    <?php echo e($packages->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('select[name="status"], select[name="sort"]').on('change', function() {
        $(this).closest('form').submit();
    });

    $('form[action*="toggle-status"]').on('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Yakin ingin mengubah status paket ini?')) {
            this.submit();
        }
    });

    $('form[action*="toggle-featured"]').on('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Yakin ingin mengubah status featured paket ini?')) {
            this.submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/admin/packages/index.blade.php ENDPATH**/ ?>