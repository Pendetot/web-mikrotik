<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Account Settings</h4>
      </div>
      <div class="card-body">
        <p>Manage your account settings and security preferences.</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Profile Information</h4>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" value="<?php echo e(auth()->user()->name); ?>" readonly>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" readonly>
        </div>
        <div class="form-group">
          <label>WhatsApp Number</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fab fa-whatsapp"></i>
              </div>
            </div>
            <input type="text" class="form-control" value="<?php echo e(auth()->user()->whatsapp ?? 'Not provided'); ?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Role</label>
          <input type="text" class="form-control" value="<?php echo e(ucfirst(auth()->user()->role)); ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-12 col-md-6 col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Change Password</h4>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo e(route('settings.update-password')); ?>">
          <?php echo csrf_field(); ?>
          <div class="form-group">
            <label>Current Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-lock"></i>
                </div>
              </div>
              <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="current_password" required>
              <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
          
          <div class="form-group">
            <label>New Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-lock"></i>
                </div>
              </div>
              <input type="password" class="form-control pwstrength <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" data-indicator="pwindicator" required>
              <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div id="pwindicator" class="pwindicator">
              <div class="bar"></div>
              <div class="label"></div>
            </div>
          </div>
          
          <div class="form-group">
            <label>Confirm New Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-lock"></i>
                </div>
              </div>
              <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password_confirmation" required>
              <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
          
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Account Information</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <td class="font-weight-600">Account Status</td>
                <td>
                  <span class="badge badge-<?php echo e(auth()->user()->is_active ? 'success' : 'danger'); ?>">
                    <?php echo e(auth()->user()->is_active ? 'Active' : 'Inactive'); ?>

                  </span>
                </td>
              </tr>
              <tr>
                <td class="font-weight-600">Member Since</td>
                <td><?php echo e(auth()->user()->created_at->format('d M Y')); ?></td>
              </tr>
              <tr>
                <td class="font-weight-600">Last Updated</td>
                <td><?php echo e(auth()->user()->updated_at->format('d M Y H:i')); ?></td>
              </tr>
              <tr>
                <td class="font-weight-600">Email Verified</td>
                <td>
                  <span class="badge badge-<?php echo e(auth()->user()->email_verified_at ? 'success' : 'warning'); ?>">
                    <?php echo e(auth()->user()->email_verified_at ? 'Verified' : 'Unverified'); ?>

                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    <?php if(session('success')): ?>
        iziToast.success({
            title: 'Success',
            message: '<?php echo e(session('success')); ?>',
            position: 'topRight'
        });
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        iziToast.error({
            title: 'Error',
            message: '<?php echo e(session('error')); ?>',
            position: 'topRight'
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/settings/settings.blade.php ENDPATH**/ ?>