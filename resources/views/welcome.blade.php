<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-xl font-bold text-gray-700">BuildEasy</h1>

            <div>
                @if(Auth::check())
                    @if(Auth::user()->role == 'customer')
                        <a href="{{ url('customer/dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Customer Dashboard</a>
                    @elseif(Auth::user()->role == 'supplier')
                        <a href="{{ url('supplier/dashboard') }}" class="px-4 py-2 bg-green-500 text-white rounded">Supplier Dashboard</a>
                    @else
                        <a href="{{ url('admin/dashboard') }}" class="px-4 py-2 bg-red-500 text-white rounded">Admin Dashboard</a>
                    @endif
                
                <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="inline-block ml-2">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded">Logout</button>
        </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Login</a>
                    <!-- <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 text-white rounded ml-2">Register</a> -->
                @endif
            </div>
        </div>
    </nav>

    <div class="flex items-center justify-center h-screen">
        <div class="text-center">
            <h2 class="text-4xl font-bold text-gray-700">Welcome to BuildEasy</h2>
            <p class="mt-2 text-gray-600">Find and manage construction materials easily.</p>

            @if(!Auth::check())
                <div class="mt-6">
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-green-500 text-white rounded text-lg">Get Started</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>