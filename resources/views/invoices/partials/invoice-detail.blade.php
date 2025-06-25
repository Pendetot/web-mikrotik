<div class="invoice">
  <div class="invoice-print">
    <div class="row">
      <div class="col-lg-12">
        <div class="invoice-title">
          <h2>Invoice</h2>
          <div class="invoice-number">{{ $invoice->invoice_number }}</div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <address>
              <strong>Billed To:</strong><br>
              {{ $invoice->user->name }}<br>
              {{ $invoice->user->email }}<br>
              @if($invoice->user->phone)
                {{ $invoice->user->phone }}<br>
              @endif
              @if($invoice->user->address)
                {{ $invoice->user->address }}
              @endif
            </address>
          </div>
          <div class="col-md-6 text-md-right">
            <address>
              <strong>Company:</strong><br>
              {{ config('app.name') }}<br>
              {{ config('app.company_address', 'Company Address') }}<br>
              {{ config('app.company_email', 'info@company.com') }}
            </address>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <address>
              <strong>Payment Method:</strong><br>
              @if($invoice->payment_method)
                {{ ucfirst($invoice->payment_method) }}<br>
              @else
                Not specified<br>
              @endif
              @if($invoice->payment_reference)
                Ref: {{ $invoice->payment_reference }}
              @endif
            </address>
          </div>
          <div class="col-md-6 text-md-right">
            <address>
              <strong>Invoice Date:</strong><br>
              {{ $invoice->created_at->format('F d, Y') }}<br>
              <strong>Due Date:</strong><br>
              {{ $invoice->due_date->format('F d, Y') }}<br>
              @if($invoice->paid_at)
                <strong>Paid Date:</strong><br>
                {{ $invoice->paid_at->format('F d, Y') }}
              @endif
            </address>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="section-title">Order Summary</div>
        <p class="section-lead">Subscription package details and pricing breakdown.</p>
        <div class="table-responsive">
          <table class="table table-striped table-hover table-md">
            <thead>
              <tr>
                <th data-width="40">#</th>
                <th>Item</th>
                <th>Description</th>
                <th class="text-center">Duration</th>
                <th class="text-right">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>
                  <strong>{{ $invoice->package->name ?? 'Package' }}</strong>
                  @if($invoice->package && $invoice->package->category)
                    <br><small class="text-muted">{{ $invoice->package->category->name }}</small>
                  @endif
                </td>
                <td>
                  @if($invoice->package)
                    {{ $invoice->package->description }}
                    @if($invoice->package->features)
                      <br>
                      <small class="text-muted">
                        @foreach(json_decode($invoice->package->features, true) as $feature)
                          • {{ $feature }}<br>
                        @endforeach
                      </small>
                    @endif
                  @endif
                </td>
                <td class="text-center">
                  @if($invoice->package)
                    {{ $invoice->package->duration }} {{ $invoice->package->duration_type }}
                  @else
                    -
                  @endif
                </td>
                <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="row mt-4">
          <div class="col-lg-8">
            <div class="section-title">Payment Information</div>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Status:</strong> 
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
                  <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($invoice->status) }}</span>
                </p>
                @if($invoice->payment_method)
                  <p><strong>Payment Method:</strong> {{ ucfirst($invoice->payment_method) }}</p>
                @endif
                @if($invoice->payment_reference)
                  <p><strong>Reference:</strong> {{ $invoice->payment_reference }}</p>
                @endif
              </div>
              <div class="col-md-6">
                <p><strong>Created:</strong> {{ $invoice->created_at->format('F d, Y H:i') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>
                @if($invoice->paid_at)
                  <p><strong>Paid Date:</strong> {{ $invoice->paid_at->format('F d, Y H:i') }}</p>
                @endif
              </div>
            </div>
            
            @if($invoice->notes)
              <div class="mt-3">
                <div class="section-title">Notes</div>
                <p class="text-muted">{{ $invoice->notes }}</p>
              </div>
            @endif
          </div>
          
          <div class="col-lg-4 text-right">
            <div class="invoice-detail-item">
              <div class="invoice-detail-name">Subtotal</div>
              <div class="invoice-detail-value">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
            </div>
            @if($invoice->tax_amount > 0)
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">Tax</div>
                <div class="invoice-detail-value">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
              </div>
            @endif
            <hr class="mt-2 mb-2">
            <div class="invoice-detail-item">
              <div class="invoice-detail-name">Total</div>
              <div class="invoice-detail-value invoice-detail-value-lg">
                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>