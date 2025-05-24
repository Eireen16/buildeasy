<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupplierRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.supplier-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'company_name' => 'required',
            'license_number' => 'required',
        ]);

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'supplier',
        ]);

        // Create supplier profile
        Supplier::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'license_number' => $request->license_number,
            'is_approved' => false,
        ]);

        return redirect('/login')->with('success', 'Registration submitted. Please wait for admin approval.');
    }
}

