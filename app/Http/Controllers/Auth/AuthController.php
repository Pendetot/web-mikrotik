<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        // Ambil paket yang aktif untuk ditampilkan di halaman login
        $packages = Package::active()
                          ->orderBy('featured', 'desc')
                          ->orderBy('price', 'asc')
                          ->get();
        
        return view('auth.login', compact('packages'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'whatsapp';
        
        $user = User::where($loginField, $credentials['login'])
                   ->where('is_active', true)
                   ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            
            $request->session()->regenerate();
            
            return $this->redirectAfterLogin($user);
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    public function showRegister(): View
    {
        // Ambil paket yang dipilih dari parameter URL jika ada
        $selectedPackage = null;
        if (request('package')) {
            $selectedPackage = Package::active()->find(request('package'));
        }
        
        // Ambil semua paket aktif
        $packages = Package::active()
                          ->orderBy('featured', 'desc')
                          ->orderBy('price', 'asc')
                          ->get();
        
        return view('auth.register', compact('packages', 'selectedPackage'));
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        // Jika ada paket yang dipilih, redirect ke halaman checkout
        if ($request->package_id) {
            $package = Package::active()->find($request->package_id);
            if ($package) {
                return redirect()->route('checkout', ['package' => $package->id]);
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectAfterLogin(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}