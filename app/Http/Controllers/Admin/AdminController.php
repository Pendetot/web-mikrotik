<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::users()->count(),
            'active_users' => User::users()->active()->count(),
            'total_admins' => User::admins()->count(),
            'inactive_users' => User::users()->where('is_active', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function showUser(User $user)
    {
        $user->load(['subscriptions.package', 'invoices']);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,admin',
            'is_active' => 'boolean',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $data = $request->only(['name', 'email', 'role', 'is_active']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diperbarui');
    }
    
    public function toggleUserStatus(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
                        ->with('success', "User berhasil {$status}");
    }
    
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil dihapus');
    }
}