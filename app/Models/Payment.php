<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_type',
        'status',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'description',
        'notes',
        'processed_at',
        'expired_at',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'expired_at' => 'datetime',
        'gateway_response' => 'json',
        'metadata' => 'json'
    ];

    protected $dates = [
        'processed_at',
        'expired_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<', now());
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isExpired()
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->transaction_id) {
                $payment->transaction_id = 'TXN-' . time() . '-' . rand(1000, 9999);
            }
        });
    }
}