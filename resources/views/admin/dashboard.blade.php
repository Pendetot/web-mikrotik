@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Welcome to Admin Dashboard</h4>
      </div>
      <div class="card-body">
        <p>Hello <strong>{{ auth()->user()->name }}</strong>, welcome to your admin dashboard!</p>
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
          {{ $stats['total_users'] ?? 0 }}
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
          {{ $stats['total_packages'] ?? 0 }}
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
          {{ $stats['active_users'] ?? 0 }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Dashboard specific JavaScript
    console.log('Admin Dashboard loaded for: {{ auth()->user()->name }}');
    
    // Auto refresh stats every 30 seconds
    setInterval(function() {
        // You can add AJAX call here to refresh stats without page reload
        console.log('Stats can be refreshed here via AJAX');
    }, 30000);
});
</script>
@endpush