<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
        'price_paid',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_paid' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'subscription_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= Carbon::today();
    }

    public function isExpired()
    {
        return $this->end_date < Carbon::today() || $this->status === 'expired';
    }

    public function daysRemaining()
    {
        if ($this->status !== 'active') {
            return 0;
        }
        
        return Carbon::today()->diffInDays($this->end_date, false);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere('end_date', '<', Carbon::today());
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}