<?php

namespace App\View\Components;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartComposer
{
    public function compose(View $view)
    {
        $cartItemsCount = 0;
        
        if (Auth::check()) {
            $customer = Customer::where('user_id', Auth::id())->first();
            $cartItemsCount = $customer && $customer->cart ? $customer->cart->total_items : 0;
        }
        
        $view->with('cartItemsCount', $cartItemsCount);
    }
}