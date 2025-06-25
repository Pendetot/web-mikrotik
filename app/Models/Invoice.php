<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'package_id',
        'subscription_id',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_reference',
        'due_date',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     */
    protected $appends = ['is_overdue'];

    /**
     * Get the user that owns the invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the invoice.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the subscription associated with the invoice.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    /**
     * Check if invoice is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date->isPast();
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'paid' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'refunded' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Scope a query to only include pending invoices.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                     ->where('due_date', '<', now());
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid($paymentMethod = null, $paymentReference = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
        ]);
    }

    /**
     * Mark invoice as failed.
     */
    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Mark invoice as cancelled.
     */
    public function markAsCancelled()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Generate invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        
        // Get the last invoice number for current month
        $lastInvoice = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastInvoice) {
            // Extract sequence number from last invoice
            $lastNumber = (int) substr($lastInvoice->invoice_number, -5);
            $sequence = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $sequence = '00001';
        }
        
        return $prefix . $year . $month . $sequence;
    }
}