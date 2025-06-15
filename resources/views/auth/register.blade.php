<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Account Type - BuildEasy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #a8d8e8 100%);
            min-height: 100vh;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #a8d8e8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .account-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid rgba(226, 232, 240, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .account-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .account-card:hover::before {
            left: 100%;
        }
        
        .account-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 30px 60px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .customer-card:hover {
            border-color: rgba(59, 130, 246, 0.6);
            box-shadow: 0 30px 60px rgba(59, 130, 246, 0.15);
        }
        
        .supplier-card:hover {
            border-color: rgba(16, 185, 129, 0.6);
            box-shadow: 0 30px 60px rgba(16, 185, 129, 0.15);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(1deg); }
            66% { transform: translateY(-8px) rotate(-1deg); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .icon-bounce {
            animation: iconBounce 2s ease-in-out infinite;
        }
        
        @keyframes iconBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        .construction-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M20 20h60v60H20z" fill="none" stroke="white" stroke-width="2"/><path d="M30 30h40v40H30z" fill="none" stroke="white" stroke-width="1"/><circle cx="25" cy="25" r="3" fill="white"/><circle cx="75" cy="25" r="3" fill="white"/><circle cx="25" cy="75" r="3" fill="white"/><circle cx="75" cy="75" r="3" fill="white"/></svg>');
            background-size: 120px 120px;
            z-index: 0;
        }
        
        .card-content {
            position: relative;
            z-index: 1;
        }
        
        .feature-badge {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 20px rgba(102, 126, 234, 0.3));
        }
    </style>
</head>
<body>
    <div class="gradient-bg flex items-center justify-center p-6">
        <div class="construction-pattern"></div>

        <!-- Main Content Container -->
        <div class="glass-effect rounded-3xl p-8 md:p-12 max-w-4xl w-full slide-in relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-6">
                    <img src="{{ asset('images/BuildEasyLogo.png') }}" alt="BuildEasy Logo" class="h-16 w-auto logo-glow">
                    <h1 class="text-3xl font-bold gradient-text ml-4">BuildEasy</h1>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Choose Your Account Type
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Select the option that best describes your role in the construction industry to get started with BuildEasy
                </p>
            </div>

            <!-- Account Type Cards -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Customer Card -->
                <a href="{{ url('register/customer') }}" class="block">
                    <div class="account-card customer-card rounded-2xl p-8 h-full cursor-pointer">
                        <div class="card-content text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Customer</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Perfect for contractors, builders, and construction professionals who need to source materials for their projects.
                            </p>
                            
                            <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-100">
                                <span class="text-blue-700 font-semibold text-lg">Register as Customer</span>
                                <svg class="w-5 h-5 text-blue-600 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Supplier Card -->
                <a href="{{ url('register/supplier') }}" class="block">
                    <div class="account-card supplier-card rounded-2xl p-8 h-full cursor-pointer">
                        <div class="card-content text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 icon-bounce" style="animation-delay: 0.2s;">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Supplier</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Ideal for material suppliers, distributors, and manufacturers who want to reach more customers and grow their business.
                            </p>
                            
                            <div class="bg-green-50 rounded-xl p-4 border-2 border-green-100">
                                <span class="text-green-700 font-semibold text-lg">Register as Supplier</span>
                                <svg class="w-5 h-5 text-green-600 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Bottom Section -->
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-4">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium underline">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Add smooth hover effects and interactions
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.account-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.03)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
                
                // Add click animation
                card.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(102, 126, 234, 0.1)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.pointerEvents = 'none';
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Parallax effect for floating elements
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallaxElements = document.querySelectorAll('.floating-animation');
                
                parallaxElements.forEach((element, index) => {
                    const speed = 0.3 + (index * 0.1);
                    element.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });
        });
        
        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>