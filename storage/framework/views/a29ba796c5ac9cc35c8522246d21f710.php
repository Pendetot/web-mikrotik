<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Welcome to Admin Dashboard</h4>
      </div>
      <div class="card-body">
        <p>Hello <strong><?php echo e(auth()->user()->name); ?></strong>, welcome to your admin dashboard!</p>
        <p class="text-muted">Manage your system efficiently from here.</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="fas fa-users"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Users</h4>
        </div>
        <div class="card-body">
          <?php echo e($stats['total_users'] ?? 0); ?>

        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success">
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
  
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning">
        <i class="fas fa-user-check"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Active Users</h4>
        </div>
        <div class="card-body">
          <?php echo e($stats['active_users'] ?? 0); ?>

        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Dashboard specific JavaScript
    console.log('Admin Dashboard loaded for: <?php echo e(auth()->user()->name); ?>');
    
    // Auto refresh stats every 30 seconds
    setInterval(function() {
        // You can add AJAX call here to refresh stats without page reload
        console.log('Stats can be refreshed here via AJAX');
    }, 30000);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>