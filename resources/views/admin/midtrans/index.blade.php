@extends('layouts.app')

@section('title', 'Pengaturan Midtrans')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Pengaturan Midtrans Payment Gateway</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-primary" id="testConnectionBtn">
                        <i class="fas fa-plug"></i> Test Koneksi
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="midtransSettingsForm">
                    @csrf
                    
                    <!-- Environment Settings -->
                    <div class="section-title">Environment Settings</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_environment">Environment</label>
                                <select class="form-control" id="midtrans_environment" name="midtrans_environment" required>
                                    <option value="sandbox" {{ ($settings['midtrans_environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>
                                        Sandbox (Testing)
                                    </option>
                                    <option value="production" {{ ($settings['midtrans_environment'] ?? 'sandbox') === 'production' ? 'selected' : '' }}>
                                        Production (Live)
                                    </option>
                                </select>
                                <small class="form-text text-muted">Pilih environment untuk testing atau production</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_merchant_id">Merchant ID</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="midtrans_merchant_id" 
                                       name="midtrans_merchant_id"
                                       value="{{ $settings['midtrans_merchant_id'] ?? '' }}"
                                       placeholder="Masukkan Merchant ID"
                                       required>
                                <small class="form-text text-muted">Merchant ID dari dashboard Midtrans</small>
                            </div>
                        </div>
                    </div>

                    <!-- API Keys -->
                    <div class="section-title">API Keys</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_server_key">Server Key</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="midtrans_server_key" 
                                           name="midtrans_server_key"
                                           value="{{ $settings['midtrans_server_key'] ?? '' }}"
                                           placeholder="Masukkan Server Key"
                                           required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('midtrans_server_key')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Server Key untuk API authentication</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_client_key">Client Key</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="midtrans_client_key" 
                                       name="midtrans_client_key"
                                       value="{{ $settings['midtrans_client_key'] ?? '' }}"
                                       placeholder="Masukkan Client Key"
                                       required>
                                <small class="form-text text-muted">Client Key untuk frontend integration</small>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="section-title">Security Settings</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="midtrans_enable_3ds" 
                                           name="midtrans_enable_3ds"
                                           {{ ($settings['midtrans_enable_3ds'] ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="midtrans_enable_3ds">
                                        Enable 3D Secure
                                    </label>
                                    <small class="form-text text-muted">Aktifkan 3D Secure untuk keamanan tambahan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="midtrans_sanitized" 
                                           name="midtrans_sanitized"
                                           {{ ($settings['midtrans_sanitized'] ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="midtrans_sanitized">
                                        Enable Input Sanitization
                                    </label>
                                    <small class="form-text text-muted">Sanitasi input untuk keamanan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Settings -->
                    <div class="section-title">URL Settings</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_notification_url">Notification URL</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="midtrans_notification_url" 
                                       name="midtrans_notification_url"
                                       value="{{ $settings['midtrans_notification_url'] ?? url('/api/midtrans/notification') }}"
                                       placeholder="https://yourdomain.com/api/midtrans/notification">
                                <small class="form-text text-muted">URL untuk menerima notifikasi dari Midtrans</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_finish_url">Finish URL</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="midtrans_finish_url" 
                                       name="midtrans_finish_url"
                                       value="{{ $settings['midtrans_finish_url'] ?? url('/payment/success') }}"
                                       placeholder="https://yourdomain.com/payment/success">
                                <small class="form-text text-muted">URL redirect setelah pembayaran berhasil</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_unfinish_url">Unfinish URL</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="midtrans_unfinish_url" 
                                       name="midtrans_unfinish_url"
                                       value="{{ $settings['midtrans_unfinish_url'] ?? url('/payment/pending') }}"
                                       placeholder="https://yourdomain.com/payment/pending">
                                <small class="form-text text-muted">URL untuk pembayaran yang belum selesai</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_error_url">Error URL</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="midtrans_error_url" 
                                       name="midtrans_error_url"
                                       value="{{ $settings['midtrans_error_url'] ?? url('/payment/error') }}"
                                       placeholder="https://yourdomain.com/payment/error">
                                <small class="form-text text-muted">URL untuk pembayaran yang gagal</small>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="section-title">Payment Methods</div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Metode Pembayaran yang Diaktifkan</label>
                                <div class="row">
                                    @php
                                        $availablePayments = [
                                            'credit_card' => 'Credit Card',
                                            'bca_va' => 'BCA Virtual Account',
                                            'bni_va' => 'BNI Virtual Account',
                                            'bri_va' => 'BRI Virtual Account',
                                            'permata_va' => 'Permata Virtual Account',
                                            'mandiri_va' => 'Mandiri Virtual Account',
                                            'cimb_va' => 'CIMB Niaga Virtual Account',
                                            'other_va' => 'Other Virtual Account',
                                            'gopay' => 'GoPay',
                                            'shopeepay' => 'ShopeePay',
                                            'dana' => 'DANA',
                                            'ovo' => 'OVO',
                                            'linkaja' => 'LinkAja',
                                            'qris' => 'QRIS',
                                            'indomaret' => 'Indomaret',
                                            'alfamart' => 'Alfamart',
                                            'akulaku' => 'Akulaku'
                                        ];
                                        $enabledPayments = $settings['midtrans_enabled_payments'] ?? ['credit_card', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'gopay', 'shopeepay', 'dana'];
                                    @endphp
                                    
                                    @foreach($availablePayments as $key => $label)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="payment_{{ $key }}" 
                                                   name="midtrans_enabled_payments[]"
                                                   value="{{ $key }}"
                                                   {{ in_array($key, $enabledPayments) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="payment_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">Pilih metode pembayaran yang akan tersedia untuk pengguna</small>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Settings -->
                    <div class="section-title">Expiry Settings</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_expiry_duration">Durasi Expiry</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="midtrans_expiry_duration" 
                                       name="midtrans_expiry_duration"
                                       value="{{ $settings['midtrans_expiry_duration'] ?? 24 }}"
                                       min="1" 
                                       max="1440"
                                       required>
                                <small class="form-text text-muted">Durasi berlaku pembayaran (1-1440)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="midtrans_custom_expiry_unit">Unit Waktu</label>
                                <select class="form-control" id="midtrans_custom_expiry_unit" name="midtrans_custom_expiry_unit" required>
                                    <option value="minutes" {{ ($settings['midtrans_custom_expiry_unit'] ?? 'hours') === 'minutes' ? 'selected' : '' }}>
                                        Menit
                                    </option>
                                    <option value="hours" {{ ($settings['midtrans_custom_expiry_unit'] ?? 'hours') === 'hours' ? 'selected' : '' }}>
                                        Jam
                                    </option>
                                    <option value="days" {{ ($settings['midtrans_custom_expiry_unit'] ?? 'hours') === 'days' ? 'selected' : '' }}>
                                        Hari
                                    </option>
                                </select>
                                <small class="form-text text-muted">Unit waktu untuk durasi expiry</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Webhook Logs Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Webhook Logs</h4>
                <div class="card-header-action">
                    <button type="button" class="btn btn-warning btn-sm" onclick="clearLogs()">
                        <i class="fas fa-trash"></i> Clear Logs
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="refreshLogs()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="webhookLogsTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Transaction Status</th>
                                <th>Amount</th>
                                <th>Payment Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>Belum ada log webhook</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Webhook Details -->
<div class="modal fade" id="webhookDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Webhook Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="webhookDetailContent"></pre>
            </div>
        </div>
    </div>
</div>

<style>
.section-title {
    font-size: 1.1em;
    font-weight: 600;
    color: #34395e;
    margin: 1.5rem 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.section-title:first-child {
    margin-top: 0;
}

.custom-control-label {
    font-weight: 500;
}

.form-text {
    font-size: 0.8em;
}

#webhookDetailContent {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    font-size: 0.875em;
    max-height: 400px;
    overflow-y: auto;
}
</style>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling.querySelector('button i');
    
    if (field.type === 'password') {
        field.type = 'text';
        button.classList.remove('fa-eye');
        button.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        button.classList.remove('fa-eye-slash');
        button.classList.add('fa-eye');
    }
}

// Test connection
document.getElementById('testConnectionBtn').addEventListener('click', function() {
    const serverKey = document.getElementById('midtrans_server_key').value;
    const environment = document.getElementById('midtrans_environment').value;
    
    if (!serverKey) {
        alert('Masukkan Server Key terlebih dahulu');
        return;
    }
    
    const btn = this;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    btn.disabled = true;
    
    fetch('{{ route("admin.settings.midtrans.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            server_key: serverKey,
            environment: environment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message + '\nMerchant ID: ' + (data.data?.merchant_id || 'N/A'));
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat test koneksi');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});

// Submit form
document.getElementById('midtransSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    submitBtn.disabled = true;
    
    fetch('{{ route("admin.settings.midtrans.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + (data.message || 'Terjadi kesalahan'));
            if (data.errors) {
                console.error('Validation errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menyimpan pengaturan');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Reset form
function resetForm() {
    if (confirm('Yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
        document.getElementById('midtransSettingsForm').reset();
    }
}

// Clear logs
function clearLogs() {
    if (confirm('Yakin ingin menghapus semua log webhook?')) {
        fetch('{{ route("admin.settings.midtrans.clear-logs") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                refreshLogs();
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat menghapus log');
        });
    }
}

// Refresh logs
function refreshLogs() {
    // Implementation for refreshing webhook logs
    console.log('Refreshing webhook logs...');
    // You can implement AJAX call to fetch latest logs here
}

// Load webhook logs on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load initial webhook logs if needed
    refreshLogs();
});
</script>