<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildEasy - Construction Materials Made Simple</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #a8d8e8 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #a8d8e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(-10px) rotate(-1deg); }
        }
        
        .pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); }
            50% { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8), 0 0 40px rgba(118, 75, 162, 0.4); }
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .construction-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M20 20h60v60H20z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M30 30h40v40H30z" fill="none" stroke="currentColor" stroke-width="1"/><circle cx="25" cy="25" r="3" fill="currentColor"/><circle cx="75" cy="25" r="3" fill="currentColor"/><circle cx="25" cy="75" r="3" fill="currentColor"/><circle cx="75" cy="75" r="3" fill="currentColor"/></svg>');
            background-size: 80px 80px;
            z-index: -1;
        }
        
        .feature-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-lg py-4 sticky top-0 z-50 border-b border-gray-200/20">
        <div class="container mx-auto flex justify-between items-center px-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                     <img src="{{ asset('images/BuildEasyLogo.png') }}">
                </div>
                <h1 class="text-2xl font-bold gradient-text">BuildEasy</h1>
            </div>

            <div class="flex items-center space-x-3">
                @if(Auth::check())
                    @if(Auth::user()->role == 'customer')
                        <a href="{{ url('customer/dashboard') }}" class="nav-link px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-300 hover-lift">Customer Dashboard</a>
                    @elseif(Auth::user()->role == 'supplier')
                        <a href="{{ url('supplier/dashboard') }}" class="nav-link px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-300 hover-lift">Supplier Dashboard</a>
                    @else
                        <a href="{{ url('admin/dashboard') }}" class="nav-link px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-300 hover-lift">Admin Dashboard</a>
                    @endif
                
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-300 hover-lift">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300 hover-lift">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center gradient-bg">
        <div class="construction-bg"></div>

        <!-- Main Content -->
        <div class="text-center z-10 max-w-4xl mx-auto px-6">
            <div class="mb-8 mt-5">
                <h2 class="text-6xl md:text-7xl text-white mb-6 leading-tight">
                    Welcome to <br>
                    <span class="text-6xl md:text-7xl font-bold text-white mb-6 leading-tight">
                        BuildEasy
                    </span>
                </h2>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Unifying suppliers, empowering sustainability <br> your all-in-one platform for construction material sourcing.
                </p>
            </div>

            <!-- CTA Buttons -->
            @if(!Auth::check())
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-gray-800 rounded-xl text-lg font-semibold hover-lift pulse-glow transition-all duration-300 shimmer">
                        Register & Get Started Today
                    </a>
                </div>
            @endif

            <!-- Features Preview -->
            <div class="grid md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="feature-card p-6 rounded-2xl hover-lift">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Find Materials Easily</h3>
                    <p class="text-gray-600 text-sm">Multiple suppliers in one place</p>
                </div>
                
                <div class="feature-card p-6 rounded-2xl hover-lift">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Trusted Supplier</h3>
                    <p class="text-gray-600 text-sm">Verified suppliers with quality guarantees and reviews</p>
                </div>
                
                <div class="feature-card p-6 rounded-2xl hover-lift">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Greener Construction Approach</h3>
                    <p class="text-gray-600 text-sm">Empowering sustainable builds through smarter material choices</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Parallax effect for floating elements
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallaxElements = document.querySelectorAll('.floating-animation');
                
                parallaxElements.forEach((element, index) => {
                    const speed = 0.5 + (index * 0.1);
                    element.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });
            
            // Add click animations to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>
</body>
</html>