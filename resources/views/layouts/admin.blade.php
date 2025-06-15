<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'BuildEasy') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid #fff;
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.2);
            border-left: 3px solid #fff;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .header-bar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        .logo {
            max-height: 40px;
            width: auto;
        }
        .user-menu {
            position: relative;
        }
        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            padding: 20px;
            background: white;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        footer {
            background: #343a40 !important;
            color: white !important;
            margin-top: auto;
        }
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-brand h4 {
            color: white;
            margin: 10px 0 0 0;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <!-- Logo Section -->
                <div class="sidebar-brand">
                    <h4>Admin Panel</h4>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-users me-2"></i>
                        View Users
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.pending.suppliers') ? 'active' : '' }}" href="{{ route('admin.pending.suppliers') }}">
                        <i class="fas fa-user-clock me-2"></i>
                        Pending Suppliers
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('admin.materials*') ? 'active' : '' }}" href="{{ route('admin.materials') }}">
                        <i class="fas fa-boxes me-2"></i>
                        View Materials
                    </a>
                    
                    <a class="nav-link" href="{{ route('admin.categories') }}">
                        <i class="fas fa-tags me-2"></i>
                        Material Categories
                    </a>
                </nav>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header Bar -->
                <div class="header-bar">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col">
                                <img src="{{ asset('images/BuildEasyLogo.png') }}" alt="BuildEasy Logo" class="logo" style="width: 80px; height: auto;">
                                <h6 style="padding-left: 10px;">BuildEasy</h6>
                            </div>
                            <div class="col-auto">
                                <!-- User Account Dropdown -->
                                <div class="dropdown user-menu">
                                    <button class="btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->username ?? 'A', 0, 1)) }}
                                            </div>
                                            <div class="text-start d-none d-md-block">
                                                <div class="fw-semibold">{{ Auth::user()->name ?? Auth::user()->username }}</div>
                                                <small class="text-muted">Administrator</small>
                                            </div>
                                            <i class="fas fa-chevron-down ms-2"></i>
                                        </div>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-sign-out-alt me-2"></i>
                                                    Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Page Content -->
                <div class="content-wrapper">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>