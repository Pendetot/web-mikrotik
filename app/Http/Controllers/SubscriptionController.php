<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $subscriptions = UserSubscription::with(['package', 'package.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $activeSubscriptions = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();
        
        $totalSpent = UserSubscription::where('user_id', $user->id)
            ->whereIn('status', ['active', 'expired'])
            ->sum('price_paid');
        
        return view('subscriptions.index', compact(
            'subscriptions',
            'activeSubscriptions', 
            'totalSpent'
        ));
    }
    
    public function show($id)
    {
        $subscription = UserSubscription::with(['package', 'package.category'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('subscriptions.show', compact('subscription'));
    }
    
    public function renew($id)
    {
        $subscription = UserSubscription::with('package')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Only active subscriptions can be renewed.');
        }
        
        $package = $subscription->package;
        
        return view('subscriptions.renew', compact('subscription', 'package'));
    }
    
    public function processRenewal(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);
        
        $subscription = UserSubscription::with('package')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Only active subscriptions can be renewed.');
        }
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Renewal request submitted successfully. Please complete the payment process.');
    }
    
    public function cancel($id)
    {
        $subscription = UserSubscription::where('user_id', Auth::id())
            ->findOrFail($id);
        
        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Only active subscriptions can be cancelled.');
        }
        
        $subscription->update([
            'status' => 'cancelled',
            'notes' => 'Cancelled by user on ' . now()->format('Y-m-d H:i:s')
        ]);
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled successfully.');
    }
}