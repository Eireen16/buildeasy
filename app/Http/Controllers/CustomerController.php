<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display the customer profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('customer.profile', compact('user', 'customer'));
    }

        /**
     * Display the customer profile page but in edit mode
     */
    public function editProfile()
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('customer.profile-edit', compact('user', 'customer'))->with('editMode', true);
    }

    /**
     * Update the customer profile page 
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bank_details' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user info
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update customer info
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->bank_details = $request->bank_details;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imagePath = $image->store('profile_pictures', 'public');
            $customer->profile_picture = '/storage/' . $imagePath;
        }

       $customer->save();

       return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
    }
}

