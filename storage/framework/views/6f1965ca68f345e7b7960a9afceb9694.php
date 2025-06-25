<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Welcome to Dashboard</h4>
      </div>
      <div class="card-body">
        <p>Hello <strong><?php echo e(auth()->user()->name); ?></strong>, welcome to your dashboard!</p>
        <p class="text-muted">You are logged in as <?php echo e(ucfirst(auth()->user()->role)); ?>.</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">
          Account Details        
        </div>
      </div>
      <div class="card-icon shadow-success bg-success">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Status</h4>
        </div>
        <div class="card-body">
          <span class="badge badge-<?php echo e(auth()->user()->is_active ? 'success' : 'danger'); ?>">
            <?php echo e(auth()->user()->is_active ? 'Active' : 'Inactive'); ?>

          </span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Add any dashboard-specific JavaScript here
    console.log('Dashboard loaded for user: <?php echo e(auth()->user()->name); ?>');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/dashboard.blade.php ENDPATH**/ ?>