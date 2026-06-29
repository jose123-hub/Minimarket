<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            $loginType = $request->login_type;

        if ($loginType === 'employee' && !in_array($role, ['admin', 'cashier'])) {
        Auth::logout();

        return back()->withErrors([
        'email' => 'This portal is for employees only.',
         ]);
        }

        if ($loginType === 'client' && $role !== 'client') {
        Auth::logout();

        return back()->withErrors([
        'email' => 'This portal is for customers only.',
         ]);
        }
            if ($role === 'admin') {
                return redirect('/dashboard');
            }

            if ($role === 'cashier') {
                return redirect('/cashier/dashboard');
            }

            return redirect('/client/catalog');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function storeClient(Request $request)
    {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()
          ->withErrors([
        'email' => 'Las credenciales no son correctas.',
         ])
          ->withInput($request->only('email', 'login_type'));
    }

    $request->session()->regenerate();

    $user = Auth::user();

    $role = strtolower($user->roleInfo?->name ?? $user->role ?? '');

    if ($role !== 'client') {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('client.login')
            ->withErrors([
                'email' => 'This login is only for clients.',
            ])
            ->withInput($request->only('email'));
    }

    return redirect()->route('catalog');
    }
}