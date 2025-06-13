<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Share categories with all views that need the navigation
        try {
            $categories = Category::with('subCategories')->get();
            View::share('categories', $categories);
        } catch (\Exception $e) {
            // Fallback to empty collection if database error
            View::share('categories', collect());
        }
    }
}