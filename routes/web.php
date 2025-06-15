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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\SupplierOrderHistoryController;
use App\Http\Controllers\CustomerOrderHistoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\MaterialCalculatorController;
use App\Http\Controllers\CustomerLikeController;
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

    //customer search material
    Route::get('/customer/search', [CustomerMaterialController::class, 'search'])->name('customer.search');

    // category and subcategory routes
    Route::get('/customer/category/{categoryId}', [CustomerMaterialController::class, 'showByCategory'])->name('customer.category');
    Route::get('/customer/category/{categoryId}/subcategory/{subCategoryId}', [CustomerMaterialController::class, 'showBySubCategory'])->name('customer.subcategory');

    // Cart routes
    Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/customer/cart/add', [CartController::class, 'addToCart'])->name('customer.cart.add');
    Route::patch('/customer/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('customer.cart.update');
    Route::delete('/customer/cart/remove/{id}', [CartController::class, 'removeItem'])->name('customer.cart.remove');

    //Checkout routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/success', [OrderController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [OrderController::class, 'cancel'])->name('checkout.cancel');

    //customer view my order page route
    Route::get('/customer/orders', [App\Http\Controllers\CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::put('/customer/orders/{order}/cancel', [App\Http\Controllers\CustomerOrderController::class, 'cancelOrder'])->name('customer.orders.cancel');

    //customer view order history route
    Route::get('/customer/orders/history', [CustomerOrderHistoryController::class, 'index'])->name('customer.orders.history');

    //customer track order  and view pickup address route
    Route::get('/track-order/{order_id}', [CustomerOrderController::class, 'trackOrder'])->name('customer.track.order');
    Route::get('/orders/{order}/pickup', [CustomerOrderController::class, 'showPickupAddress'])->name('orders.pickup');

    //Customer write reviews route
    Route::get('/customer/order-items/{orderItem}/review', [ReviewController::class, 'create'])->name('customer.reviews.create');
    Route::post('/customer/order-items/{orderItem}/review', [ReviewController::class, 'store'])->name('customer.reviews.store');
    Route::get('/customer/order-items/{orderItem}/can-review', [ReviewController::class, 'canReview'])->name('customer.reviews.can-review');

    //Material Calculator route
    Route::get('/material-calculator', [MaterialCalculatorController::class, 'index'])->name('customer.calculator.index');
    
    // Calculator API endpoints
    Route::post('/calculator/paint', [MaterialCalculatorController::class, 'calculatePaint'])->name('calculator.paint');
    Route::post('/calculator/tiles', [MaterialCalculatorController::class, 'calculateTiles'])->name('calculator.tiles');
    Route::post('/calculator/bricks', [MaterialCalculatorController::class, 'calculateBricks'])->name('calculator.bricks');
    Route::post('/calculator/concrete', [MaterialCalculatorController::class, 'calculateConcrete'])->name('calculator.concrete');
    
    // Calculator Save Project management 
    Route::post('/calculator/save-project', [MaterialCalculatorController::class, 'saveProject'])->name('calculator.save-project');
    Route::get('/calculator/saved-projects', [MaterialCalculatorController::class, 'getSavedProjects'])->name('calculator.saved-projects');
    Route::delete('/calculator/project/{id}', [MaterialCalculatorController::class, 'deleteProject'])->name('calculator.delete-project');

    // Likes routes
    Route::get('/customer/likes', [CustomerLikeController::class, 'index'])->name('customer.likes.index');
    Route::post('/customer/likes/toggle/{material}', [CustomerLikeController::class, 'toggle'])->name('customer.likes.toggle');
    Route::delete('/customer/likes/remove/{material}', [CustomerLikeController::class, 'remove'])->name('customer.likes.remove');

    //Route::get('/debug-cart', [CartController::class, 'debugCart'])->name('debug.cart');
});


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

    //Supplier search route
    Route::get('/supplier/search', [SupplierMaterialController::class, 'search'])->name('supplier.search');

    // Category routes
    Route::get('/category/{categoryId}', [SupplierMaterialController::class, 'categoryView'])->name('supplier.category');
    Route::get('/category/{categoryId}/subcategory/{subCategoryId}', [SupplierMaterialController::class, 'subCategoryView'])->name('supplier.subcategory');

    //Supplier Edit and Delete Material
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

    //Supplier view my order page route
    Route::get('/supplier/orders', [App\Http\Controllers\SupplierOrderController::class, 'index'])->name('supplier.orders.index');
    Route::put('/supplier/orders/{order}/status', [App\Http\Controllers\SupplierOrderController::class, 'updateStatus'])->name('supplier.orders.updateStatus');
    Route::put('/supplier/orders/{order}/cancel', [App\Http\Controllers\SupplierOrderController::class, 'cancelOrder'])->name('supplier.orders.cancel');

    //supplier go to history page route
     Route::get('/supplier/orders/history', [SupplierOrderHistoryController::class, 'index'])->name('supplier.orders.history');

    // Generate Sales routes
    Route::get('/sales', [SalesController::class, 'index'])->name('supplier.sales.index');
    Route::get('/sales/export', [SalesController::class, 'export'])->name('supplier.sales.export');
  });


// Admin dashboard route after log in
Route::get('/admin/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('admin.dashboard');
    }
    return abort(403); // Forbidden
})->middleware('auth')->name('admin.dashboard');

// Admin Dashboard with User Management
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// View specific user details
Route::get('/users/{id}', [AdminController::class, 'viewUser'])->name('admin.view-user');

// Delete user
Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');

// Admin view Pending suppliers list
Route::get('/admin/pending-suppliers', [AdminController::class, 'pendingSuppliers'])->name('admin.pending.suppliers');

// Admin Approve supplier
Route::post('/admin/approve-supplier/{id}', [AdminController::class, 'approveSupplier'])->name('admin.approve.supplier');

//Admin Delete supplier
Route::delete('/admin/delete-supplier/{id}', [AdminController::class, 'deleteSupplier'])->name('admin.delete.supplier');

// Backward compatibility route
Route::get('/users', [AdminController::class, 'viewUsers'])->name('admin.view-users');

// New Materials Management Routes
Route::get('/admin/materials', [AdminController::class, 'viewMaterials'])->name('admin.materials');
Route::get('/admin/materials/{id}', [AdminController::class, 'viewMaterial'])->name('admin.materials.show');
Route::delete('/admin/materials/{id}', [AdminController::class, 'deleteMaterial'])->name('admin.materials.delete');

// Category Management Routes
Route::get('/categories', [AdminController::class, 'manageCategories'])->name('admin.categories');
Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::put('/admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

// Subcategory Management Routes
Route::post('/subcategories', [AdminController::class, 'storeSubCategory'])->name('admin.subcategories.store');
Route::put('/admin/subcategories/{id}', [AdminController::class, 'updateSubCategory'])->name('admin.subcategories.update');
Route::delete('/admin/subcategories/{id}', [AdminController::class, 'deleteSubCategory'])->name('admin.subcategories.delete');

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');





