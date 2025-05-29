<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BuildEasy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo-container img {
            height: 70px;
            width: 70px;
        }
        .nav-pills .nav-link {
            border-radius: 0;
            padding: 10px 20px;
            font-weight: 500;
        }
        .nav-container {
            border-bottom: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .daily-discover {
            background-color: #a8d8e8;
            padding: 15px;
            margin-top: 10px;
        }
        .icon-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #0275d8;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .header-icon-container {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .search-container {
            position: relative;
            width: 100%;
            max-width: 300px;
        }
        .search-container input {
            border-radius: 20px;
            padding-right: 40px;
            border: 1px solid #ced4da;
        }
        .search-container button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: none;
            border: none;
            color: #0275d8;
        }
        .nav-labels {
            font-size: 0.75rem;
            display: block;
            text-align: center;
            margin-top: 2px;
        }
        .dropdown-menu {
            border-radius: 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Cart Badge Styles */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 10;
        }

        .cart-badge:empty {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cart-badge {
                width: 18px;
                height: 18px;
                font-size: 10px;
                top: -3px;
                right: -6px;
            }
        }

    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Header -->
        <div class="container-fluid py-2 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-2 col-sm-12">
                    <div class="logo-container">
                        <a href="{{ url('/customer/dashboard') }}">
                            <img src="{{ asset('images/BuildEasyLogo.png') }}" alt="BuildEasy" onerror="this.src='https://via.placeholder.com/150x50?text=CMF'">
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 my-2">
                    <div class="search-container">
                        <input type="text" class="form-control" placeholder="Search Products">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button> 
                        <!-- <form action="{{ route('materials.search') }}" method="GET" class="flex w-full">
                        <input type="text" name="query" placeholder="Search Listings" value="{{ request('query') }}"
                             class="w-full px-4 py-2 border rounded-l-lg">
                        <button type="submit" class="bg-blue-600 text-white px-4 rounded-r-lg">
                           🔍
                        </button> -->
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="header-icon-container justify-content-end">
                        <div class="text-center">
                            <button class="icon-btn" onclick="window.location.href='{{ url('/customer/chats') }}'">
                                <i class="fas fa-comments"></i>
                            </button>
                            <span class="nav-labels">Chats</span>
                        </div>
                        
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <button class="icon-btn" onclick="window.location.href='{{ url('/customer/cart') }}'">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                @if(isset($cartItemsCount) && $cartItemsCount > 0)
                                    <span class="cart-badge" id="cart-count">{{ $cartItemsCount > 99 ? '99+' : $cartItemsCount }}</span>
                                @endif
                            </div>
                            <span class="nav-labels">My cart</span>
                        </div>
                        
                        <div class="text-center">
                            <div class="dropdown">
                                <button class="icon-btn dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                </button>
                                <span class="nav-labels">My account</span>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                    <li><a class="dropdown-item" href="{{ url('/customer/profile') }}">Manage Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="container-fluid nav-container">
            <ul class="nav nav-pills">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Category</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('/customer/category/cement') }}">Cement</a></li>
                        <li><a class="dropdown-item" href="{{ url('/customer/category/steel') }}">Steel</a></li>
                        <li><a class="dropdown-item" href="{{ url('/customer/category/bricks') }}">Bricks</a></li>
                        <li><a class="dropdown-item" href="{{ url('/customer/category/sand') }}">Sand</a></li>
                        <li><a class="dropdown-item" href="{{ url('/customer/category/tools') }}">Tools</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/customer/budget-calculator') }}">Budget Calculator</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/customer/orders') }}">My Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/customer/notifications') }}">Notification</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="container-fluid mt-5 py-3 bg-light text-center">
            <p>&copy; 2025 BuildEasy. All rights reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to update cart count badge
        function updateCartCount(count) {
            const cartBadge = document.getElementById('cart-count');
            const cartButton = document.querySelector('.position-relative');
            
            if (count > 0) {
                if (cartBadge) {
                    cartBadge.textContent = count > 99 ? '99+' : count;
                } else {
                    // Create badge if it doesn't exist
                    const badge = document.createElement('span');
                    badge.className = 'cart-badge';
                    badge.id = 'cart-count';
                    badge.textContent = count > 99 ? '99+' : count;
                    cartButton.appendChild(badge);
                }
            } else {
                // Remove badge if count is 0
                if (cartBadge) {
                    cartBadge.remove();
                }
            }
        }
    </script>

</body>
</html>