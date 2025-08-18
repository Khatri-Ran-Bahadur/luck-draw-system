<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lucky Draw System - Win Amazing Prizes!' ?></title>
    <meta name="description" content="<?= $description ?? 'Join our exciting lucky draws and win amazing cash prizes and products! Choose between cash draws and product draws.' ?>">

    <!-- Tailwind CSS with custom configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-bg-alt {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .btn-primary {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
        }

        .btn-secondary {
            @apply bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
        }

        .btn-success {
            @apply bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
        }

        .btn-danger {
            @apply bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl;
        }

        .nav-link {
            @apply text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10;
        }

        .nav-link.active {
            @apply text-white bg-white/20;
        }

        .mobile-menu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-icon {
            @apply w-8 h-8 bg-white/10 backdrop-blur-md rounded-lg flex items-center justify-center mr-3 border border-white/20;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center group">
                        <div class="relative">
                            <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl mr-4 group-hover:scale-110 transition-all duration-300 shadow-lg group-hover:shadow-xl border border-white/20">
                                <i class="fas fa-dice text-2xl text-white animate-bounce-slow"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full animate-pulse-slow"></div>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-white">LuckyDraw</span>
                            <p class="text-xs text-white/80 -mt-1 font-medium">Win • Play • Prosper</p>
                        </div>
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-1">
                    <a href="/" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= current_url() == base_url() ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="/cash-draws" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= strpos(current_url(), '/cash') !== false ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-dollar-sign mr-2"></i>Cash Draws
                    </a>
                    <a href="/product-draws" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= strpos(current_url(), '/products') !== false ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-gift mr-2"></i>Product Draws
                    </a>
                    <a href="/winners" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= strpos(current_url(), '/winners') !== false ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-trophy mr-2"></i>Winners
                    </a>
                    <a href="/faq" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= strpos(current_url(), '/faq') !== false ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-question-circle mr-2"></i>FAQ
                    </a>
                    <a href="/contact" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 <?= strpos(current_url(), '/contact') !== false ? 'bg-white/20' : '' ?>">
                        <i class="fas fa-envelope mr-2"></i>Contact
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <?php if (session()->get('user_id')): ?>
                        <!-- User Menu -->
                        <div class="relative ml-3">
                            <div>
                                <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        <?= substr(session()->get('username') ?: 'U', 0, 1) ?>
                                    </div>
                                    <span class="ml-2 text-gray-700 font-medium hidden md:block"><?= session()->get('username') ?></span>
                                    <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                                </button>
                            </div>

                            <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="<?= base_url('dashboard') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Dashboard
                                </a>
                                <a href="<?= base_url('my-winnings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-trophy mr-2"></i>
                                    My Winnings
                                </a>
                                <a href="<?= base_url('wallet') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-wallet mr-2"></i>
                                    My Wallet
                                </a>
                                <a href="<?= base_url('cash-draws') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    Cash Draws
                                </a>
                                <a href="<?= base_url('product-draws') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-gift mr-2"></i>
                                    Product Draws
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Sign out
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="text-white/90 hover:text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="/register" class="bg-white/10 backdrop-blur-md text-white hover:bg-white/20 px-6 py-2 rounded-xl text-sm font-medium transition-all duration-300 border border-white/20">
                            <i class="fas fa-user-plus mr-2"></i>Get Started
                        </a>
                    <?php endif; ?>

                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 rounded-xl text-white/90 hover:text-white hover:bg-white/10 transition-colors duration-200" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="lg:hidden mobile-menu max-h-0 overflow-hidden">
            <div class="px-4 pt-2 pb-4 space-y-2 bg-white/5 backdrop-blur-md border-t border-white/10">
                <a href="/" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-home text-white"></i>
                    </div>
                    Home
                </a>
                <?php if (session()->get('user_id')): ?>
                    <a href="/my-winnings" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                        My Winnings
                    </a>
                <?php endif; ?>
                <a href="/cash-draws" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    Cash Draws
                </a>
                <a href="/product-draws" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-gift text-white"></i>
                    </div>
                    Product Draws
                </a>
                <a href="/winners" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-trophy text-white"></i>
                    </div>
                    Winners
                </a>
                <a href="/faq" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-question-circle text-white"></i>
                    </div>
                    FAQ
                </a>
                <a href="/contact" class="text-white/90 hover:text-white px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/10 flex items-center">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center mr-3 border border-white/20">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    Contact
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white/95 backdrop-blur-md border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative flex items-center shadow-xl" role="alert">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-lg text-green-500"></i>
                </div>
                <span class="block sm:inline font-medium"><?= session()->getFlashdata('success') ?></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times text-green-500 hover:text-green-700 transition-colors duration-200"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-white/95 backdrop-blur-md border border-red-200 text-red-700 px-6 py-4 rounded-2xl relative flex items-center shadow-xl" role="alert">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-lg text-red-500"></i>
                </div>
                <span class="block sm:inline font-medium"><?= session()->getFlashdata('error') ?></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                    <i class="fas fa-times text-red-500 hover:text-red-700 transition-colors duration-200"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl mr-3">
                            <i class="fas fa-dice text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Lucky Draw System</h3>
                            <p class="text-sm text-gray-400">Win Amazing Prizes</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">Join our exciting lucky draws and stand a chance to win incredible cash prizes and amazing products!</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Home</a></li>
                        <li><a href="/cash-draws" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Cash Draws</a></li>
                        <li><a href="/product-draws" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Product Draws</a></li>
                        <li><a href="/winners" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Winners</a></li>
                        <li><a href="/faq" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Contact Us</a></li>
                        <li><a href="/terms" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Terms & Conditions</a></li>
                        <li><a href="/privacy" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Privacy Policy</a></li>
                        <li><a href="/help" class="text-gray-300 hover:text-white transition-colors duration-200 text-sm">Help Center</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6 text-white">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-facebook text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 hover:bg-blue-500 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 hover:bg-pink-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                    <div class="mt-6">
                        <h5 class="text-sm font-medium text-gray-300 mb-3">Newsletter</h5>
                        <div class="flex">
                            <input type="email" placeholder="Enter your email" class="flex-1 px-3 py-2 bg-gray-800 border border-gray-700 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-r-lg transition-colors duration-200">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; <?= date('Y') ?> Lucky Draw System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const isOpen = mobileMenu.classList.contains('max-h-0');

            if (isOpen) {
                mobileMenu.classList.remove('max-h-0');
                mobileMenu.classList.add('max-h-96');
            } else {
                mobileMenu.classList.remove('max-h-96');
                mobileMenu.classList.add('max-h-0');
            }
        }

        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(function(message) {
                message.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>