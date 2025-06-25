<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')); ?>">
        <?php echo e(config('app.name')); ?>

      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')); ?>">
        <?php echo e(substr(config('app.name'), 0, 2)); ?>

      </a>
    </div>
    <ul class="sidebar-menu">

      <?php if(auth()->user()->role === 'admin'): ?>
        <!-- ADMIN ONLY MENU -->
        <li class="menu-header">Admin Panel</li>
        
        <!-- Admin Dashboard -->
        <li class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Admin Dashboard</span>
          </a>
        </li>

        <!-- Kelola Pengguna -->
        <li class="<?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('admin.users.index')); ?>">
            <i class="fas fa-users"></i>
            <span>Kelola Pengguna</span>
          </a>
        </li>

        <!-- Kelola Paket Langganan -->
        <li class="<?php echo e(request()->routeIs('admin.packages*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('admin.packages.index')); ?>">
            <i class="fas fa-box"></i>
            <span>Kelola Paket Langganan</span>
          </a>
        </li>

        <!-- Kelola Pembayaran - Changed from dropdown to direct button -->
        <li class="<?php echo e(request()->routeIs('admin.payments*') || request()->routeIs('admin.invoices*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('admin.payments.index')); ?>">
            <i class="fas fa-credit-card"></i>
            <span>Kelola Pembayaran</span>
            <?php if(isset($pending_payments_count) && $pending_payments_count > 0): ?>
              <span class="badge badge-warning"><?php echo e($pending_payments_count); ?></span>
            <?php endif; ?>
          </a>
        </li>

        <li class="menu-header">Laporan & Analisis</li>

        <!-- System Logs -->
        <li class="<?php echo e(request()->routeIs('admin.logs*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('admin.logs')); ?>">
            <i class="fas fa-list-alt"></i>
            <span>System Logs</span>
          </a>
        </li>

        <li class="menu-header">Pengaturan</li>
        
        <!-- Settings -->
<li class="dropdown <?php echo e(request()->routeIs('admin.settings*') || request()->routeIs('admin.midtrans*') ? 'active' : ''); ?>">
  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
    <i class="fas fa-cog"></i>
    <span>Pengaturan</span>
  </a>
  <ul class="dropdown-menu">
    <li class="<?php echo e(request()->routeIs('admin.midtrans*') ? 'active' : ''); ?>">
      <a class="nav-link" href="<?php echo e(route('admin.midtrans.index')); ?>">
        <i class="fas fa-money-check-alt"></i>
        <span>Midtrans</span>
      </a>
    </li>
    <!-- Add more settings items here if needed -->
    <li class="<?php echo e(request()->routeIs('admin.settings.general') ? 'active' : ''); ?>">
      <a class="nav-link" href="<?php echo e(route('admin.settings.general')); ?>">
        <i class="fas fa-cogs"></i>
        <span>General Settings</span>
      </a>
    </li>
    <li class="<?php echo e(request()->routeIs('admin.settings.payment') ? 'active' : ''); ?>">
      <a class="nav-link" href="<?php echo e(route('admin.settings.payment')); ?>">
        <i class="fas fa-credit-card"></i>
        <span>Payment Settings</span>
      </a>
    </li>
    <li class="<?php echo e(request()->routeIs('admin.settings.notifications') ? 'active' : ''); ?>">
      <a class="nav-link" href="<?php echo e(route('admin.settings.notifications')); ?>">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
      </a>
    </li>
    <li class="<?php echo e(request()->routeIs('admin.settings.system') ? 'active' : ''); ?>">
      <a class="nav-link" href="<?php echo e(route('admin.settings.system')); ?>">
        <i class="fas fa-server"></i>
        <span>System Settings</span>
      </a>
    </li>
  </ul>
</li>

      <?php else: ?>
        <!-- USER ONLY MENU -->
        <li class="menu-header">Main</li>
        
        <!-- User Dashboard -->
        <li class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
            <i class="fas fa-fire"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- Browse Packages -->
        <li class="<?php echo e(request()->routeIs('packages*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('packages.index')); ?>">
            <i class="fas fa-shopping-bag"></i>
            <span>Browse Packages</span>
          </a>
        </li>

        <!-- My Subscriptions -->
        <li class="<?php echo e(request()->routeIs('subscriptions*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('subscriptions.index')); ?>">
            <i class="fas fa-shopping-cart"></i>
            <span>My Subscriptions</span>
          </a>
        </li>

        <!-- My Invoices -->
        <li class="<?php echo e(request()->routeIs('invoices*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('invoices.index')); ?>">
            <i class="fas fa-file-invoice"></i>
            <span>My Invoices</span>
          </a>
        </li>

        <li class="menu-header">Account</li>

        <!-- Profile -->
        <li class="<?php echo e(request()->routeIs('profile*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('profile')); ?>">
            <i class="fas fa-user"></i>
            <span>Profile</span>
          </a>
        </li>

        <!-- Settings -->
        <li class="<?php echo e(request()->routeIs('settings*') ? 'active' : ''); ?>">
          <a class="nav-link" href="<?php echo e(route('settings')); ?>">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
          </a>
        </li>

      <?php endif; ?>

    </ul>

    <!-- User Info Card -->
    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <div class="card">
        <div class="card-body text-center">
          <?php if(auth()->user()->role === 'admin'): ?>
            <h6>Admin Panel</h6>
            <p class="text-muted small"><?php echo e(auth()->user()->name); ?></p>
            <div class="badge badge-danger">
              <?php echo e(ucfirst(auth()->user()->role)); ?>

            </div>
            <div class="mt-2">
              <small class="text-muted">
                Online: <span class="text-success">●</span>
              </small>
            </div>
          <?php else: ?>
            <h6>Welcome</h6>
            <p class="text-muted small"><?php echo e(auth()->user()->name); ?></p>
            <div class="badge badge-primary">
              <?php echo e(ucfirst(auth()->user()->role)); ?>

            </div>
            <div class="mt-2">
              <small class="text-muted">
                Status: <span class="badge badge-<?php echo e(auth()->user()->is_active ? 'success' : 'warning'); ?>">
                  <?php echo e(auth()->user()->is_active ? 'Active' : 'Inactive'); ?>

                </span>
              </small>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </aside>
</div><?php /**PATH /storage/emulated/0/p/kunyuk/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>