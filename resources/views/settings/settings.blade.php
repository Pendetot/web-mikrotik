@extends('layouts.app')

@section('title', 'Settings')

@section('content')
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
          <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
        </div>
        <div class="form-group">
          <label>WhatsApp Number</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fab fa-whatsapp"></i>
              </div>
            </div>
            <input type="text" class="form-control" value="{{ auth()->user()->whatsapp ?? 'Not provided' }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Role</label>
          <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}" readonly>
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
        <form method="POST" action="{{ route('settings.update-password') }}">
          @csrf
          <div class="form-group">
            <label>Current Password</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-lock"></i>
                </div>
              </div>
              <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
              @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
              <input type="password" class="form-control pwstrength @error('password') is-invalid @enderror" name="password" data-indicator="pwindicator" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
              <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>
              @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
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
                  <span class="badge badge-{{ auth()->user()->is_active ? 'success' : 'danger' }}">
                    {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
              </tr>
              <tr>
                <td class="font-weight-600">Member Since</td>
                <td>{{ auth()->user()->created_at->format('d M Y') }}</td>
              </tr>
              <tr>
                <td class="font-weight-600">Last Updated</td>
                <td>{{ auth()->user()->updated_at->format('d M Y H:i') }}</td>
              </tr>
              <tr>
                <td class="font-weight-600">Email Verified</td>
                <td>
                  <span class="badge badge-{{ auth()->user()->email_verified_at ? 'success' : 'warning' }}">
                    {{ auth()->user()->email_verified_at ? 'Verified' : 'Unverified' }}
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(session('success'))
        iziToast.success({
            title: 'Success',
            message: '{{ session('success') }}',
            position: 'topRight'
        });
    @endif
    
    @if(session('error'))
        iziToast.error({
            title: 'Error',
            message: '{{ session('error') }}',
            position: 'topRight'
        });
    @endif
});
</script>
@endpush