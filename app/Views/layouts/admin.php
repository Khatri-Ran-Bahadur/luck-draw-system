<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Lucky Draw System</title>

    <!-- Favicon -->
    <?php
    $settingModel = new \App\Models\SettingModel();
    $favicon = $settingModel->getSetting('favicon');
    if (!empty($favicon)): ?>
        <link rel="icon" type="image/<?= pathinfo($favicon, PATHINFO_EXTENSION) === 'ico' ? 'x-icon' : 'png' ?>"
            href="<?= base_url('uploads/settings/' . $favicon) ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <?php endif; ?>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* Custom CSS Variables for Modern Glassmorphism */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-bg-dark: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.3);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --glass-backdrop: blur(12px);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-tertiary: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            background: #ffffff;
            color: #1f2937;
            min-height: 100vh;
        }

        /* Glassmorphism Components */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-backdrop);
            -webkit-backdrop-filter: var(--glass-backdrop);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.6);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .sidebar-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-right: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .gradient-tertiary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Navigation Components */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-item-active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.25);
        }

        .nav-item-inactive {
            color: #6b7280;
        }

        .nav-item-inactive:hover {
            background: rgba(156, 163, 175, 0.1);
            color: #374151;
        }

        .nav-group {
            cursor: pointer;
        }

        .nav-group-active {
            background: rgba(102, 126, 234, 0.08);
            color: #374151;
        }

        /* Input Glass Style */
        .input-glass {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .input-glass::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .input-glass:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(59, 130, 246, 0.6);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.15);
            transform: translateY(-1px);
        }

        /* Floating animation for background elements */
        .floating-animation {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar-mobile.open {
                transform: translateX(0);
            }

            .main-content-mobile {
                margin-left: 0;
            }
        }

        /* Stat Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.6);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Fixed sidebar layout */
        .sidebar-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar-scrollable {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-fixed-bottom {
            flex-shrink: 0;
            border-top: 1px solid rgba(229, 231, 235, 0.3);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Hide scrollbar but keep functionality */
        .sidebar-scrollable::-webkit-scrollbar {
            width: 2px;
        }

        .sidebar-scrollable::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }

        .sidebar-scrollable::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 2px;
        }

        .sidebar-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.5);
        }
    </style>
</head>

<body class="min-h-screen bg-white relative transition-colors duration-200">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-20 w-72 h-72 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full blur-3xl opacity-30 floating-animation"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-gradient-to-r from-purple-50 to-pink-50 rounded-full blur-3xl opacity-30 floating-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-full blur-3xl opacity-30 floating-animation" style="animation-delay: 4s;"></div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-30 bg-black/20 backdrop-blur-sm lg:hidden hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 sidebar-glass lg:translate-x-0 sidebar-mobile sidebar-container">
        <!-- Sidebar header -->
        <div class="flex h-20 items-center px-4 border-b border-gray-200/50 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">L</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">QuickLucky</h1>
                    <p class="text-xs text-gray-500">Admin Dashboard</p>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button id="closeSidebar" class="ml-auto p-2 rounded-lg glass hover:bg-gray-100/50 transition-all duration-200 lg:hidden" onclick="toggleSidebar()">
                <i class="fas fa-times h-5 w-5 text-gray-600"></i>
            </button>
        </div>

        <!-- Scrollable Navigation Container -->
        <div class="sidebar-scrollable">
            <!-- Navigation -->
            <nav class="px-4 py-4 space-y-2">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">Main Menu</div>

                <!-- Dashboard -->
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= url_is('admin/dashboard') ? 'nav-item-active' : 'nav-item-inactive' ?>">
                    <i class="fas fa-th-large mr-3 h-5 w-5 flex-shrink-0"></i>
                    Dashboard
                </a>

                <!-- Lucky Draw Management -->
                <div class="space-y-1">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mt-4">Draw Management</div>

                    <a href="<?= base_url('admin/cash-draws') ?>" class="nav-item <?= strpos(current_url(), 'admin/cash-draws') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-money-bill-wave mr-3 h-5 w-5 flex-shrink-0"></i>
                        Cash Draws
                    </a>

                    <a href="<?= base_url('admin/product-draws') ?>" class="nav-item <?= strpos(current_url(), 'admin/product-draws') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-gift mr-3 h-5 w-5 flex-shrink-0"></i>
                        Product Draws
                    </a>

                    <a href="<?= base_url('admin/winners') ?>" class="nav-item <?= strpos(current_url(), 'admin/winners') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-trophy mr-3"></i>
                        Winners
                    </a>

                    <a href="<?= base_url('admin/approve-claims') ?>" class="nav-item <?= strpos(current_url(), 'admin/approve-claims') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-check-circle mr-3"></i>
                        Approve Claims
                    </a>
                </div>

                <!-- User Management -->
                <div class="space-y-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">User Management</h3>
                    <a href="<?= base_url('admin/users') ?>" class="nav-item <?= strpos(current_url(), 'admin/users') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-users mr-3 h-5 w-5 flex-shrink-0"></i>
                        All Users
                    </a>
                    <a href="<?= base_url('admin/special-users') ?>" class="nav-item <?= strpos(current_url(), 'admin/special-users') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-star mr-3 h-5 w-5 flex-shrink-0"></i>
                        Special Users
                    </a>
                    <a href="<?= base_url('admin/admins') ?>" class="nav-item <?= strpos(current_url(), 'admin/admins') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-user-shield mr-3 h-5 w-5 flex-shrink-0"></i>
                        Admin Users
                    </a>
                </div>

                <!-- Finance Management -->
                <div class="space-y-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</h3>
                    <a href="<?= base_url('admin/topup-requests') ?>" class="nav-item <?= strpos(current_url(), 'admin/topup-requests') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-wallet mr-3 h-5 w-5 flex-shrink-0"></i>
                        Top-up Requests
                    </a>
                    <a href="<?= base_url('admin/withdraw-requests') ?>" class="nav-item <?= strpos(current_url(), 'admin/withdraw-requests') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-money-bill-wave mr-3 h-5 w-5 flex-shrink-0"></i>
                        Withdrawal Requests
                    </a>

                </div>

                <!-- System Management -->
                <div class="space-y-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System</h3>
                    <a href="<?= base_url('admin/contact-submissions') ?>" class="nav-item <?= strpos(current_url(), 'admin/contact-submissions') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-envelope mr-3 h-5 w-5 flex-shrink-0"></i>
                        Contact Submissions
                    </a>
                    <a href="<?= base_url('admin/notifications') ?>" class="nav-item <?= strpos(current_url(), 'admin/notifications') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-bell mr-3 h-5 w-5 flex-shrink-0"></i>
                        Notifications
                    </a>
                    <a href="<?= base_url('admin/profile') ?>" class="nav-item <?= strpos(current_url(), 'admin/profile') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-user-circle mr-3 h-5 w-5 flex-shrink-0"></i>
                        My Profile
                    </a>
                    <a href="<?= base_url('admin/admin-wallet-info') ?>" class="nav-item <?= strpos(current_url(), 'admin/admin-wallet-info') !== false ? 'nav-item-active' : 'nav-item-inactive' ?>">
                        <i class="fas fa-wallet mr-3 h-5 w-5 flex-shrink-0"></i>
                        Admin Wallet
                    </a>
                </div>

                <!-- Add some padding at the bottom -->
                <div class="h-8"></div>
            </nav>
        </div>

        <!-- Fixed bottom section -->
        <div class="sidebar-fixed-bottom">
            <!-- Settings section -->
            <div class="p-3">
                <a href="<?= base_url('admin/settings') ?>" class="nav-item <?= url_is('admin/settings*') ? 'nav-item-active' : 'nav-item-inactive' ?>">
                    <i class="fas fa-cog mr-3 h-5 w-5 flex-shrink-0"></i>
                    Settings
                </a>
            </div>

            <!-- User section -->
            <div class="p-3">
                <div class="flex items-center space-x-3 p-2 rounded-xl glass-card hover:bg-gray-50/50 transition-all duration-200 cursor-pointer" onclick="showUserMenu()">
                    <div class="w-10 h-10 gradient-primary rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                        <span class="text-white font-medium text-sm">
                            <?= strtoupper(substr(esc(session()->get('username') ?? 'Admin'), 0, 1)) ?>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            <?= esc(session()->get('username') ?? 'Admin User') ?>
                        </p>
                        <p class="text-xs text-gray-500 truncate">Administrator</p>
                    </div>
                    <button class="p-1 rounded-md hover:bg-gray-100 transition-colors">
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:pl-64 relative main-content-mobile">
        <!-- Header -->
        <header class="sticky top-0 z-30 flex h-20 shrink-0 items-center header-glass px-4 shadow-sm sm:px-6 lg:px-8">
            <!-- Mobile menu button -->
            <button id="mobileMenuBtn" type="button" class="p-2.5 text-gray-700 lg:hidden glass rounded-xl hover:bg-white/50 transition-all duration-200" onclick="toggleSidebar()">
                <i class="fas fa-bars h-6 w-6"></i>
            </button>

            <!-- Page Title & Breadcrumb -->
            <div class="flex flex-1 items-center">
                <div class="ml-4 lg:ml-0">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        <?php
                        if (url_is('admin/dashboard')) echo 'Dashboard';
                        elseif (url_is('admin/users*')) echo 'Users';
                        elseif (url_is('admin/cash-draws*')) echo 'Cash Draws';
                        elseif (url_is('admin/product-draws*')) echo 'Product Draws';
                        elseif (url_is('admin/transactions*')) echo 'Transactions';
                        elseif (url_is('admin/notifications*')) echo 'Notifications';
                        elseif (url_is('admin/winners*')) echo 'Winners';
                        elseif (url_is('admin/contact-submissions*')) echo 'Contact Submissions';
                        elseif (url_is('admin/settings*')) echo 'Settings';

                        elseif (url_is('admin/admins*')) echo 'Administrators';
                        else echo 'Admin Panel';
                        ?>
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        <?php
                        if (url_is('admin/dashboard')) echo 'Welcome back! Here\'s what\'s happening with your system today.';
                        elseif (url_is('admin/users*')) echo 'Manage user accounts and permissions';
                        elseif (url_is('admin/cash-draws*')) echo 'Manage cash prize draws and campaigns';
                        elseif (url_is('admin/product-draws*')) echo 'Manage product giveaways and prizes';
                        elseif (url_is('admin/transactions*')) echo 'View and manage financial transactions';
                        elseif (url_is('admin/notifications*')) echo 'Monitor user activities and system notifications';
                        elseif (url_is('admin/winners*')) echo 'View draw winners and results';
                        elseif (url_is('admin/contact-submissions*')) echo 'Manage customer inquiries and support requests';
                        elseif (url_is('admin/settings*')) echo 'Configure system settings';

                        elseif (url_is('admin/admins*')) echo 'Manage administrator accounts';
                        else echo 'Manage your lucky draw system';
                        ?>
                    </p>
                </div>
            </div>

            <!-- Header Right Side -->
            <div class="flex items-center gap-x-3 ml-4">
                <!-- Search -->
                <div class="relative hidden lg:block">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Search anything..." class="input-glass pl-10 text-sm w-72">
                </div>

                <!-- Notifications -->
                <?= $this->include('components/notification_dropdown') ?>

                <!-- Profile -->
                <div class="flex items-center gap-x-3 p-2 rounded-xl bg-blue-600/90 hover:bg-blue-600 transition-all duration-200 group backdrop-blur-lg cursor-pointer" onclick="showUserMenu()">
                    <div class="w-10 h-10 gradient-primary rounded-full flex items-center justify-center shadow-md ring-2 ring-white/30">
                        <span class="text-white font-semibold text-sm">
                            <?= strtoupper(substr(esc(session()->get('username') ?? 'Admin'), 0, 1)) ?>
                        </span>
                    </div>
                    <div class="hidden lg:block">
                        <span class="text-sm font-semibold text-white group-hover:text-white transition-colors">
                            <?= esc(session()->get('username') ?? 'Admin User') ?>
                        </span>
                        <p class="text-xs text-white/80 group-hover:text-white transition-colors">Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down text-white/80 text-xs"></i>
                </div>

                <!-- Logout -->
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-x-2 px-4 py-3 text-sm font-medium text-gray-700 hover:text-red-600 rounded-xl glass hover:bg-red-50/50 transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt h-5 w-5 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="hidden sm:block">Logout</span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="py-6 transition-colors duration-200 bg-white min-h-screen">
            <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- User Menu Modal (hidden by default) -->
    <div id="userMenu" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" onclick="hideUserMenu()"></div>
        <div class="fixed bottom-4 left-4 lg:left-68 w-64 bg-white rounded-2xl shadow-2xl border border-gray-200/50 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 gradient-primary rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">
                            <?= strtoupper(substr(esc(session()->get('username') ?? 'Admin'), 0, 1)) ?>
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?= esc(session()->get('username') ?? 'Admin User') ?></p>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <a href="<?= base_url('admin/profile') ?>" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-user-circle mr-3"></i>
                    My Profile
                </a>
                <hr class="my-2">
                <a href="<?= base_url('logout') ?>" class="flex items-center px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Sign Out
                </a>
            </div>
        </div>
    </div>

    <script>
        // Sidebar functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // User menu functionality
        function showUserMenu() {
            document.getElementById('userMenu').classList.remove('hidden');
        }

        function hideUserMenu() {
            document.getElementById('userMenu').classList.add('hidden');
        }

        // Close sidebar when clicking outside on mobile
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Escape to close menus
            if (e.key === 'Escape') {
                hideUserMenu();
                if (window.innerWidth <= 1024) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                }
            }

            // Ctrl/Cmd + K for search focus
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[placeholder="Search anything..."]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });

        // Auto-hide notifications after interaction
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>

</html>