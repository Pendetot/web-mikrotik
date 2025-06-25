<?php $__env->startSection('title', 'Kelola Pengguna'); ?>

<?php $__env->startSection('content'); ?>
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
                                    <select name="role" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Role</option>
                                        <option value="user" <?php echo e(request('role') === 'user' ? 'selected' : ''); ?>>User</option>
                                        <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                    </select>
                                    <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Aktif</option>
                                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Nonaktif</option>
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
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary text-white mr-3">
                                            <?php echo e(substr($user->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-weight-600"><?php echo e($user->name); ?></div>
                                            <div class="text-muted small"><?php echo e($user->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo e($user->role === 'admin' ? 'danger' : 'primary'); ?>">
                                        <?php echo e(ucfirst($user->role)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo e($user->is_active ? 'success' : 'warning'); ?>">
                                        <?php echo e($user->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <?php echo e($user->created_at->format('d M Y')); ?>

                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.users.show', $user)); ?>" 
                                           class="btn btn-info btn-sm" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" 
                                              action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" 
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" 
                                                    class="btn btn-<?php echo e($user->is_active ? 'secondary' : 'success'); ?> btn-sm" 
                                                    title="<?php echo e($user->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                                <i class="fas fa-<?php echo e($user->is_active ? 'times' : 'check'); ?>"></i>
                                            </button>
                                        </form>
                                        <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" 
                                              action="<?php echo e(route('admin.users.destroy', $user)); ?>" 
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Tidak ada pengguna ditemukan</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($users->hasPages()): ?>
                <div class="d-flex justify-content-center">
                    <?php echo e($users->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/admin/users/index.blade.php ENDPATH**/ ?>