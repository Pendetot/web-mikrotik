@extends('layouts.app')

@section('title', 'My Invoices')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>My Invoices</h4>
        <div class="card-header-action">
          <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown">
              <i class="fas fa-filter"></i> Filter
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ route('invoices.index') }}">All Status</a>
              <a class="dropdown-item" href="{{ route('invoices.index', ['status' => 'pending']) }}">Pending</a>
              <a class="dropdown-item" href="{{ route('invoices.index', ['status' => 'paid']) }}">Paid</a>
              <a class="dropdown-item" href="{{ route('invoices.index', ['status' => 'failed']) }}">Failed</a>
              <a class="dropdown-item" href="{{ route('invoices.index', ['status' => 'cancelled']) }}">Cancelled</a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        @if($invoices->count() > 0)
          <div class="table-responsive table-invoice">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Invoice ID</th>
                  <th>Package</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($invoices as $invoice)
                <tr>
                  <td>
                    <a href="#" class="font-weight-600" onclick="showInvoiceDetail({{ $invoice->id }})">
                      {{ $invoice->invoice_number }}
                    </a>
                  </td>
                  <td class="font-weight-600">
                    {{ $invoice->package->name ?? 'N/A' }}
                    @if($invoice->package)
                      <br>
                      <small class="text-muted">{{ $invoice->package->description }}</small>
                    @endif
                  </td>
                  <td>
                    <div class="font-weight-600">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                    @if($invoice->tax_amount > 0)
                      <small class="text-muted">+ Tax: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</small>
                    @endif
                  </td>
                  <td>
                    @php
                      $badgeClass = match($invoice->status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'cancelled' => 'secondary',
                        'refunded' => 'info',
                        default => 'secondary'
                      };
                    @endphp
                    <div class="badge badge-{{ $badgeClass }}">
                      {{ ucfirst($invoice->status) }}
                    </div>
                  </td>
                  <td>
                    <div class="font-weight-600">{{ $invoice->due_date->format('M d, Y') }}</div>
                    @if($invoice->status === 'pending' && $invoice->due_date->isPast())
                      <small class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Overdue
                      </small>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-sm" onclick="showInvoiceDetail({{ $invoice->id }})">
                        <i class="fas fa-eye"></i> Detail
                      </button>
                      @if($invoice->status === 'pending')
                        <a href="{{ route('invoices.pay', $invoice->id) }}" class="btn btn-success btn-sm">
                          <i class="fas fa-credit-card"></i> Pay
                        </a>
                      @endif
                      @if($invoice->status === 'paid')
                        <button type="button" class="btn btn-info btn-sm" onclick="downloadInvoice({{ $invoice->id }})">
                          <i class="fas fa-download"></i> Download
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted">
                Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
              </div>
              {{ $invoices->links() }}
            </div>
          </div>
        @else
          <div class="text-center py-5">
            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Invoices Found</h5>
            <p class="text-muted">You don't have any invoices yet.</p>
            <a href="{{ route('packages.index') }}" class="btn btn-primary">
              <i class="fas fa-shopping-cart"></i> Browse Packages
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Invoice Detail Modal -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-file-invoice"></i> Invoice Details
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="invoiceDetailContent">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Loading invoice details...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Close
        </button>
        <div id="invoiceActions">
          <!-- Actions will be loaded dynamically -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Invoices page loaded for user: {{ auth()->user()->name }}');
});

function showInvoiceDetail(invoiceId) {
    $('#invoiceDetailModal').modal('show');
    
    // Reset modal content
    $('#invoiceDetailContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading invoice details...</p>
        </div>
    `);
    $('#invoiceActions').html('');
    
    // Load invoice details via AJAX
    $.ajax({
        url: '{{ route("invoices.show", ":id") }}'.replace(':id', invoiceId),
        method: 'GET',
        success: function(response) {
            $('#invoiceDetailContent').html(response.html);
            $('#invoiceActions').html(response.actions);
        },
        error: function(xhr) {
            $('#invoiceDetailContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load invoice details. Please try again.
                </div>
            `);
        }
    });
}

function downloadInvoice(invoiceId) {
    window.open('{{ route("invoices.download", ":id") }}'.replace(':id', invoiceId), '_blank');
}

function printInvoice() {
    window.print();
}
</script>

<style>
@media print {
    .modal-header, .modal-footer, .btn, .no-print {
        display: none !important;
    }
    
    .modal-dialog {
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush