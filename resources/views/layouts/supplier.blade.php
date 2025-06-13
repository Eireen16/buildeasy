@php
    $categories = $categories ?? \App\Models\Category::with('subCategories')->get();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .my-listing {
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

        /* Dropdown submenu styles */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            margin-left: -1px;
            border-radius: 0.375rem;
        }

        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu .dropdown-menu {
            display: none;
        }

        /* Alternative: Show subcategories on click instead of hover */
        .dropdown-submenu > .dropdown-item:after {
            display: block;
            content: " ";
            float: right;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
            border-width: 5px 0 5px 5px;
            border-left-color: #ccc;
            margin-top: 5px;
            margin-right: -10px;
        }

        .dropdown-submenu:hover > .dropdown-item:after {
            border-left-color: #adb5bd;
        }

        .dropdown-submenu.pull-left {
            float: none;
        }

        .dropdown-submenu.pull-left > .dropdown-menu {
            left: -100%;
            margin-left: 10px;
            border-radius: 0.375rem;
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
                        <a href="{{ url('/supplier/dashboard') }}">
                            <img src="{{ asset('images/BuildEasyLogo.png') }}" alt="BuildEasy" onerror="this.src='https://via.placeholder.com/150x50?text=CMF'">
                        </a>
                        <h5 style="color:blue;">Supplier Centre</h5>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 my-2">
                    <div class="search-container">
                        <form action="{{ route('supplier.search') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Search Products" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="header-icon-container justify-content-end">
                        <div class="text-center">
                            <button class="icon-btn" onclick="window.location.href='{{ url('/supplier/chats') }}'">
                                <i class="fas fa-comments"></i>
                            </button>
                            <span class="nav-labels">Chats</span>
                        </div>
                        
                        <div class="text-center">
                            <div class="dropdown">
                                <button class="icon-btn dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                </button>
                                <span class="nav-labels">My account</span>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                    <li><a class="dropdown-item" href="{{ url('/supplier/profile') }}">My Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ url('/supplier/sales') }}">Sales</a></li>
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
                        @forelse($categories as $category)
                            <li class="dropdown-submenu">
                                <a class="dropdown-item" href="{{ route('supplier.category', $category->id) }}">
                                    {{ $category->category }}
                                </a>
                                @if($category->subCategories->count() > 0)
                                    <ul class="dropdown-menu">
                                        @foreach($category->subCategories as $subCategory)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('supplier.subcategory', [$category->id, $subCategory->id]) }}">
                                                    {{ $subCategory->subcategory }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @empty
                            <li><a class="dropdown-item disabled" href="#">No categories available</a></li>
                        @endforelse
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('materials.create') }}">Add Listing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/supplier/orders') }}">My Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/supplier/notifications') }}">Notification</a>
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
</body>
</html>