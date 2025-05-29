<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\Auth\SupplierRegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SupplierMaterialController;
use App\Http\Controllers\CustomerMaterialController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
   return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Role selection page
Route::get('/register', function () {
    return view('auth.register');
})->name('register'); 

// Customer registration
Route::get('/register/customer', [CustomerRegisterController::class, 'showForm']);
Route::post('/register/customer', [CustomerRegisterController::class, 'register']);

// Supplier registration
Route::get('/register/supplier', [SupplierRegisterController::class, 'showForm']);
Route::post('/register/supplier', [SupplierRegisterController::class, 'register']);


// Customer dashboard route after log in
Route::get('/customer/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'customer') {
        return view('customer.dashboard');
    }
    return abort(403); // Forbidden
})->middleware('auth')->name('customer.dashboard');

//Customer's routes
Route::middleware(['auth'])->group(function () {
    //Customer profile view and edit route
    Route::get('/customer/profile', [CustomerController::class, 'showProfile'])->name('customer.profile');
    Route::get('/customer/profile/edit', [CustomerController::class, 'editProfile'])->name('customer.profile.edit');
    Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Customer dashboard and material routes
    Route::get('/customer/dashboard', [CustomerMaterialController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/materials/{id}', [CustomerMaterialController::class, 'show'])->name('customer.materials.show');

    // Cart routes
    Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/customer/cart/add', [CartController::class, 'addToCart'])->name('customer.cart.add');
    Route::patch('/customer/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('customer.cart.update');
    Route::delete('/customer/cart/remove/{id}', [CartController::class, 'removeItem'])->name('customer.cart.remove');
});


//!!!!SEARCH FROM CUSTOMER LAYOUT (REMOVE SOON) !!!!!!
Route::get('/search', [App\Http\Controllers\CustomerMaterialController::class, 'search'])->name('customer.materials.search');
Route::get('/materials/search', [CustomerMaterialController::class, 'search'])->name('materials.search');

// Supplier dashboard route after log in
Route::get('/supplier/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'supplier') {
        return view('supplier.dashboard');
    }
    return abort(403); // Forbidden
})->middleware('auth')->name('supplier.dashboard');

//Supplier's routes
Route::middleware(['auth'])->group(function () {
    //Supplier profile view and edit route
    Route::get('/supplier/profile', [SupplierController::class, 'showProfile'])->name('supplier.profile');
    Route::get('/supplier/profile/edit', [SupplierController::class, 'editProfile'])->name('supplier.profile.edit');
    Route::put('/supplier/profile/update', [SupplierController::class, 'updateProfile'])->name('supplier.profile.update');

    // Material creation routes
    Route::get('/supplier/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/supplier/materials', [MaterialController::class, 'store'])->name('materials.store');

    // API route for getting subcategories
    Route::get('/api/categories/{category}/subcategories', [MaterialController::class, 'getSubCategories']);

    // Supplier dashboard and material routes
    Route::get('/supplier/dashboard', [SupplierMaterialController::class, 'index'])->name('supplier.dashboard');
    Route::get('/supplier/materials/{id}', [SupplierMaterialController::class, 'show'])->name('supplier.materials.show');

    //Supplier Edit and Delete Material
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
  });


// Admin dashboard route after log in
Route::get('/admin/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('admin.dashboard');
    }
    return abort(403); // Forbidden
})->middleware('auth')->name('admin.dashboard');

// Admin view Pending suppliers list
Route::get('/admin/pending-suppliers', [AdminController::class, 'pendingSuppliers'])->name('admin.pending.suppliers');

// Admin Approve supplier
Route::post('/admin/approve-supplier/{id}', [AdminController::class, 'approveSupplier'])->name('admin.approve.supplier');

//Admin Delete supplier
Route::delete('/admin/delete-supplier/{id}', [AdminController::class, 'deleteSupplier'])->name('admin.delete.supplier');

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');





