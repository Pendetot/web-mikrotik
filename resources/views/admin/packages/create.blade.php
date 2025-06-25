@extends('layouts.app')

@section('title', 'Tambah Paket Langganan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-plus"></i> Tambah Paket Langganan
                </h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="createPackageForm" action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-tag"></i> Nama Package <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="Masukkan nama paket"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label">
                                    <i class="fas fa-money-bill-wave"></i> Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" 
                                           step="0.01" 
                                           name="price" 
                                           id="price" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" 
                                           placeholder="0"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="original_price" class="form-label">
                                    <i class="fas fa-money-bill"></i> Harga Asli
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" 
                                           step="0.01" 
                                           name="original_price" 
                                           id="original_price" 
                                           class="form-control @error('original_price') is-invalid @enderror" 
                                           value="{{ old('original_price') }}" 
                                           placeholder="0">
                                    @error('original_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ada diskon</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration" class="form-label">
                                    <i class="fas fa-clock"></i> Durasi (hari) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       name="duration" 
                                       id="duration" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration') }}" 
                                       placeholder="30"
                                       min="1"
                                       required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code" class="form-label">
                                    <i class="fas fa-barcode"></i> Kode Paket
                                </label>
                                <input type="text" 
                                       name="code" 
                                       id="code" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code') }}" 
                                       placeholder="PKG-001">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Kosongkan untuk generate otomatis</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on"></i> Status
                                </label>
                                <select name="is_active" id="status" class="form-control @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Deskripsi
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="4"
                                  placeholder="Masukkan deskripsi paket (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Deskripsi akan ditampilkan kepada pengguna</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="featured" 
                                   id="featured" 
                                   class="form-check-input" 
                                   value="1"
                                   {{ old('featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">
                                <i class="fas fa-star text-warning"></i> Set sebagai paket unggulan
                            </label>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Paket
                        </button>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#createPackageForm').on('submit', function(e) {
        let isValid = true;
        
        // Reset validation states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        // Validate required fields
        const name = $('#name').val().trim();
        if (!name) {
            $('#name').addClass('is-invalid');
            showError('#name', 'Nama paket harus diisi');
            isValid = false;
        }
        
        const price = parseFloat($('#price').val());
        if (!price || price <= 0) {
            $('#price').addClass('is-invalid');
            showError('#price', 'Harga harus lebih dari 0');
            isValid = false;
        }
        
        const originalPrice = parseFloat($('#original_price').val());
        if (originalPrice && originalPrice <= price) {
            $('#original_price').addClass('is-invalid');
            showError('#original_price', 'Harga asli harus lebih tinggi dari harga jual');
            isValid = false;
        }
        
        const duration = parseInt($('#duration').val());
        if (!duration || duration <= 0) {
            $('#duration').addClass('is-invalid');
            showError('#duration', 'Durasi harus lebih dari 0 hari');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });

    // Helper function to show error
    function showError(selector, message) {
        $(selector).siblings('.invalid-feedback').text(message).show();
    }

    // Auto-generate code based on name
    $('#name').on('blur', function() {
        const name = $(this).val().trim();
        if (name && !$('#code').val()) {
            const code = 'PKG-' + name.replace(/\s+/g, '').toUpperCase().substring(0, 6);
            $('#code').val(code);
        }
    });

    // Format price input
    $('#price, #original_price').on('input', function() {
        let value = $(this).val();
        value = value.replace(/[^0-9.]/g, '');
        // Ensure only one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        $(this).val(value);
    });

    // Format duration input (only integers)
    $('#duration').on('input', function() {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        $(this).val(value);
    });

    // Calculate discount percentage
    $('#price, #original_price').on('input', function() {
        const price = parseFloat($('#price').val()) || 0;
        const originalPrice = parseFloat($('#original_price').val()) || 0;
        
        if (originalPrice > 0 && price > 0 && originalPrice > price) {
            const discount = Math.round(((originalPrice - price) / originalPrice) * 100);
            $('#discount-info').remove();
            $('#original_price').parent().after(
                '<small id="discount-info" class="form-text text-success">Diskon: ' + discount + '%</small>'
            );
        } else {
            $('#discount-info').remove();
        }
    });

    // Auto-resize textarea
    $('#description').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@endpush