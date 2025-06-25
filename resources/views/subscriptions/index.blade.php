@extends('layouts.app')

@section('title', 'My Subscriptions')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>My Subscriptions</h4>
      </div>
      <div class="card-body">
        <p>Hello <strong>{{ auth()->user()->name }}</strong>, here are your subscription details.</p>
        
        @if($subscriptions->count() > 0)
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
                @foreach($subscriptions as $subscription)
                <tr>
                  <td>
                    <strong>{{ $subscription->package->name }}</strong>
                    <br>
                    <small class="text-muted">{{ $subscription->package->description }}</small>
                  </td>
                  <td>Rp {{ number_format($subscription->price_paid, 0, ',', '.') }}</td>
                  <td>{{ $subscription->start_date->format('d M Y') }}</td>
                  <td>{{ $subscription->end_date->format('d M Y') }}</td>
                  <td>
                    @php
                      $badgeClass = match($subscription->status) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'secondary',
                        'pending' => 'warning',
                        default => 'secondary'
                      };
                    @endphp
                    <span class="badge badge-{{ $badgeClass }}">
                      {{ ucfirst($subscription->status) }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $subscription->id }}">
                        <i class="fas fa-eye"></i> Detail
                      </button>
                      @if($subscription->status === 'active')
                        <a href="{{ route('subscriptions.renew', $subscription->id) }}" class="btn btn-sm btn-success">
                          <i class="fas fa-redo"></i> Renew
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <div class="d-flex justify-content-center">
            {{ $subscriptions->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Subscriptions Found</h5>
            <p class="text-muted">You haven't subscribed to any packages yet.</p>
            <a href="{{ route('packages.index') }}" class="btn btn-primary">
              <i class="fas fa-shopping-cart"></i> Browse Packages
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@foreach($subscriptions as $subscription)
<div class="modal fade" id="detailModal{{ $subscription->id }}" tabindex="-1" role="dialog">
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
                <td>{{ $subscription->package->name }}</td>
              </tr>
              <tr>
                <td><strong>Code:</strong></td>
                <td>{{ $subscription->package->code ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td><strong>Duration:</strong></td>
                <td>{{ $subscription->package->duration }} {{ $subscription->package->duration_type }}</td>
              </tr>
              <tr>
                <td><strong>Category:</strong></td>
                <td>{{ $subscription->package->category->name ?? 'N/A' }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6>Subscription Details</h6>
            <table class="table table-borderless">
              <tr>
                <td><strong>Start Date:</strong></td>
                <td>{{ $subscription->start_date->format('d M Y') }}</td>
              </tr>
              <tr>
                <td><strong>End Date:</strong></td>
                <td>{{ $subscription->end_date->format('d M Y') }}</td>
              </tr>
              <tr>
                <td><strong>Price Paid:</strong></td>
                <td>Rp {{ number_format($subscription->price_paid, 0, ',', '.') }}</td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                  @php
                    $badgeClass = match($subscription->status) {
                      'active' => 'success',
                      'expired' => 'danger',
                      'cancelled' => 'secondary',
                      'pending' => 'warning',
                      default => 'secondary'
                    };
                  @endphp
                  <span class="badge badge-{{ $badgeClass }}">
                    {{ ucfirst($subscription->status) }}
                  </span>
                </td>
              </tr>
            </table>
          </div>
        </div>
        
        @if($subscription->package->features)
          <div class="mt-3">
            <h6>Package Features</h6>
            <ul class="list-group list-group-flush">
              @foreach(json_decode($subscription->package->features, true) as $feature)
                <li class="list-group-item">
                  <i class="fas fa-check text-success"></i> {{ $feature }}
                </li>
              @endforeach
            </ul>
          </div>
        @endif
        
        @if($subscription->notes)
          <div class="mt-3">
            <h6>Notes</h6>
            <p class="text-muted">{{ $subscription->notes }}</p>
          </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        @if($subscription->status === 'active')
          <a href="{{ route('subscriptions.renew', $subscription->id) }}" class="btn btn-success">
            <i class="fas fa-redo"></i> Renew Subscription
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Subscriptions page loaded for user: {{ auth()->user()->name }}');
    
    $('.modal').on('show.bs.modal', function() {
        $('body').addClass('modal-open');
    });
    
    $('.modal').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
    });
});
</script>
@endpush