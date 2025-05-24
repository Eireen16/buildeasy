<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // Create customer profile
        Customer::create([
            'user_id' => $user->id,
            'name' => null,
            'phone' => null,
            'address' => null,
            'bank_details' => null,
        ]);

        return redirect('/login')->with('success', 'Account created! Please log in.');
    }
}

