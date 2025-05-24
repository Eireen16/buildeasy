<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Supplier;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle the login attempt.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if supplier is approved
        if ($user->role === 'supplier') {
            $supplier = Supplier::where('user_id', $user->id)->first();

            if (!$supplier || !$supplier->is_approved) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Your account is not approved by admin yet.',
                ]);
            }

            return redirect('/supplier/dashboard');
        }

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'customer') {
            return redirect('/customer/dashboard');
        }

        return redirect('/dashboard'); // Fallback
    }

    /**
     * Logout the user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
