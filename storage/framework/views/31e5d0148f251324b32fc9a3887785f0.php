<?php $__env->startSection('title', 'My Subscriptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>My Subscriptions</h4>
      </div>
      <div class="card-body">
        <p>Hello <strong><?php echo e(auth()->user()->name); ?></strong>, here are your subscription details.</p>
        
        <?php if($subscriptions->count() > 0): ?>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Package</th>
                  <th>Price</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td>
                    <strong><?php echo e($subscription->package->name); ?></strong>
                    <br>
                    <small class="text-muted"><?php echo e($subscription->package->description); ?></small>
                  </td>
                  <td>Rp <?php echo e(number_format($subscription->price_paid, 0, ',', '.')); ?></td>
                  <td><?php echo e($subscription->start_date->format('d M Y')); ?></td>
                  <td><?php echo e($subscription->end_date->format('d M Y')); ?></td>
                  <td>
                    <?php
                      $badgeClass = match($subscription->status) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'secondary',
                        'pending' => 'warning',
                        default => 'secondary'
                      };
                    ?>
                    <span class="badge badge-<?php echo e($badgeClass); ?>">
                      <?php echo e(ucfirst($subscription->status)); ?>

                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal<?php echo e($subscription->id); ?>">
                        <i class="fas fa-eye"></i> Detail
                      </button>
                      <?php if($subscription->status === 'active'): ?>
                        <a href="<?php echo e(route('subscriptions.renew', $subscription->id)); ?>" class="btn btn-sm btn-success">
                          <i class="fas fa-redo"></i> Renew
                        </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
          
          <div class="d-flex justify-content-center">
            <?php echo e($subscriptions->links()); ?>

          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Subscriptions Found</h5>
            <p class="text-muted">You haven't subscribed to any packages yet.</p>
            <a href="<?php echo e(route('packages.index')); ?>" class="btn btn-primary">
              <i class="fas fa-shopping-cart"></i> Browse Packages
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailModal<?php echo e($subscription->id); ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subscription Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Package Information</h6>
            <table class="table table-borderless">
              <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo e($subscription->package->name); ?></td>
              </tr>
              <tr>
                <td><strong>Code:</strong></td>
                <td><?php echo e($subscription->package->code ?? 'N/A'); ?></td>
              </tr>
              <tr>
                <td><strong>Duration:</strong></td>
                <td><?php echo e($subscription->package->duration); ?> <?php echo e($subscription->package->duration_type); ?></td>
              </tr>
              <tr>
                <td><strong>Category:</strong></td>
                <td><?php echo e($subscription->package->category->name ?? 'N/A'); ?></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6>Subscription Details</h6>
            <table class="table table-borderless">
              <tr>
                <td><strong>Start Date:</strong></td>
                <td><?php echo e($subscription->start_date->format('d M Y')); ?></td>
              </tr>
              <tr>
                <td><strong>End Date:</strong></td>
                <td><?php echo e($subscription->end_date->format('d M Y')); ?></td>
              </tr>
              <tr>
                <td><strong>Price Paid:</strong></td>
                <td>Rp <?php echo e(number_format($subscription->price_paid, 0, ',', '.')); ?></td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                  <?php
                    $badgeClass = match($subscription->status) {
                      'active' => 'success',
                      'expired' => 'danger',
                      'cancelled' => 'secondary',
                      'pending' => 'warning',
                      default => 'secondary'
                    };
                  ?>
                  <span class="badge badge-<?php echo e($badgeClass); ?>">
                    <?php echo e(ucfirst($subscription->status)); ?>

                  </span>
                </td>
              </tr>
            </table>
          </div>
        </div>
        
        <?php if($subscription->package->features): ?>
          <div class="mt-3">
            <h6>Package Features</h6>
            <ul class="list-group list-group-flush">
              <?php $__currentLoopData = json_decode($subscription->package->features, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item">
                  <i class="fas fa-check text-success"></i> <?php echo e($feature); ?>

                </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <?php if($subscription->notes): ?>
          <div class="mt-3">
            <h6>Notes</h6>
            <p class="text-muted"><?php echo e($subscription->notes); ?></p>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <?php if($subscription->status === 'active'): ?>
          <a href="<?php echo e(route('subscriptions.renew', $subscription->id)); ?>" class="btn btn-success">
            <i class="fas fa-redo"></i> Renew Subscription
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    console.log('Subscriptions page loaded for user: <?php echo e(auth()->user()->name); ?>');
    
    $('.modal').on('show.bs.modal', function() {
        $('body').addClass('modal-open');
    });
    
    $('.modal').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/subscriptions/index.blade.php ENDPATH**/ ?>