<!-- Notification Dropdown Component -->
<div class="relative" id="notificationDropdown">
    <!-- Notification Bell Button -->
    <button type="button"
        onclick="toggleNotifications()"
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg">
        <i class="fas fa-bell text-xl"></i>
        <!-- Notification Badge -->
        <span id="notificationBadge"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
            0
        </span>
    </button>

    <!-- Notification Dropdown Panel -->
    <div id="notificationPanel"
        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Latest Notifications</h3>
            <div class="flex space-x-2">
                <button onclick="markAllAsRead()"
                    class="text-sm text-blue-600 hover:text-blue-800"
                    title="Mark all as read">
                    <i class="fas fa-check-double"></i>
                </button>
                <button onclick="refreshNotifications()"
                    class="text-sm text-gray-600 hover:text-gray-800"
                    title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button onclick="forceRefreshNotifications()"
                    class="text-sm text-green-600 hover:text-green-800"
                    title="Force Refresh">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="px-4 py-2 border-b border-gray-100">
            <div class="flex space-x-4">
                <button onclick="filterNotifications('all')"
                    class="notification-filter active text-sm font-medium text-blue-600 border-b-2 border-blue-600 pb-1">
                    All
                </button>
                <button onclick="filterNotifications('unread')"
                    class="notification-filter text-sm font-medium text-gray-600 hover:text-gray-900 pb-1">
                    Unread
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div id="notificationsList" class="max-h-96 overflow-y-auto">
            <!-- Loading State -->
            <div id="notificationsLoading" class="p-4 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin"></i>
                <p class="mt-2">Loading notifications...</p>
            </div>

            <!-- Empty State -->
            <div id="notificationsEmpty" class="p-6 text-center text-gray-500 hidden">
                <i class="fas fa-bell-slash text-3xl mb-2"></i>
                <p>No notifications yet</p>
            </div>

            <!-- Notifications will be loaded here -->
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 text-center">
            <?php if (session()->get('is_admin')): ?>
                <a href="<?= base_url('admin/notifications') ?>"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    Show All Notifications
                </a>
            <?php else: ?>
                <a href="<?= base_url('notifications') ?>"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-list mr-2"></i>
                    Show All Notifications
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .notification-item {
        transition: all 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f9fafb;
    }

    .notification-item.unread {
        background-color: #eff6ff;
        border-left: 4px solid #3b82f6;
    }

    .notification-filter.active {
        color: #3b82f6;
        border-bottom: 2px solid #3b82f6;
    }
</style>

<script>
    let notificationDropdownOpen = false;
    let currentFilter = 'all';
    let notifications = [];

    // Toggle notification dropdown
    function toggleNotifications() {
        const panel = document.getElementById('notificationPanel');
        notificationDropdownOpen = !notificationDropdownOpen;

        if (notificationDropdownOpen) {
            panel.classList.remove('hidden');
            // Check if session is ready before loading
            if (isSessionReady()) {
                loadNotifications();
            } else {
                // Wait a bit for session to initialize
                setTimeout(() => {
                    if (isSessionReady()) {
                        loadNotifications();
                    } else {
                        showError('Session not ready. Please refresh the page.');
                    }
                }, 500);
            }
        } else {
            panel.classList.add('hidden');
        }
    }

    // Check if session is ready
    function isSessionReady() {
        const isAuthenticated = <?= session()->get('user_id') || session()->get('is_admin') ? 'true' : 'false' ?>;
        return isAuthenticated;
    }

    // Load notifications
    function loadNotifications() {
        // Check if user is authenticated
        const isAuthenticated = <?= session()->get('user_id') || session()->get('is_admin') ? 'true' : 'false' ?>;
        if (!isAuthenticated) {
            showError('Please log in to view notifications');
            return;
        }

        const isAdmin = <?= session()->get('is_admin') ? 'true' : 'false' ?>;
        const endpoint = isAdmin ? 'admin/header-notifications' : 'notifications/user';
        const unreadOnly = currentFilter === 'unread';

        // Show loading state
        document.getElementById('notificationsLoading').classList.remove('hidden');
        document.getElementById('notificationsEmpty').classList.add('hidden');

        // Use absolute URL to avoid any base URL issues
        const url = `${window.location.origin}/${endpoint}?unread_only=${unreadOnly}&limit=10`;
        console.log('Loading notifications from:', url);

        fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin' // Include cookies/session
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    if (response.status === 401 || response.status === 403) {
                        throw new Error('Authentication required. Please refresh the page and try again.');
                    }
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    notifications = data.notifications;
                    renderNotifications(data.notifications);
                    updateNotificationBadge(data.unread_count);
                } else {
                    showError(data.message || 'Failed to load notifications');
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                showError(`Error: ${error.message}. Please try again or visit the notifications page.`);
            })
            .finally(() => {
                document.getElementById('notificationsLoading').classList.add('hidden');
            });
    }

    // Render notifications
    function renderNotifications(notificationList) {
        const container = document.getElementById('notificationsList');
        const loading = document.getElementById('notificationsLoading');
        const empty = document.getElementById('notificationsEmpty');

        loading.classList.add('hidden');

        if (notificationList.length === 0) {
            empty.classList.remove('hidden');
            container.innerHTML = '';
            container.appendChild(empty);
            return;
        }

        empty.classList.add('hidden');

        const html = notificationList.map(notification => `
        <div class="notification-item p-4 border-b border-gray-100 ${notification.is_read ? '' : 'unread'}" 
             data-id="${notification.id}">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-${notification.style?.color || 'blue'}-100 flex items-center justify-center">
                        <i class="fas ${notification.style?.icon || 'fa-bell'} text-${notification.style?.color || 'blue'}-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            ${notification.title || 'Notification'}
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${notification.priority_style?.class || 'bg-gray-100 text-gray-800'}">
                                ${notification.priority_style?.text || 'Normal'}
                            </span>
                            <span class="text-xs text-gray-500">${notification.time_ago || 'Just now'}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">${notification.message || 'No message content'}</p>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex space-x-2">
                            ${!notification.is_read ? `
                                <button onclick="markAsRead(${notification.id})" 
                                        class="text-xs text-blue-600 hover:text-blue-800">
                                    Mark as read
                                </button>
                            ` : ''}
                            <button onclick="deleteNotification(${notification.id})" 
                                    class="text-xs text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

        container.innerHTML = html;
    }

    // Update notification badge
    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationBadge');
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    // Filter notifications
    function filterNotifications(filter) {
        currentFilter = filter;

        // Update active filter button
        document.querySelectorAll('.notification-filter').forEach(btn => {
            btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
            btn.classList.add('text-gray-600');
        });

        event.target.classList.add('active', 'text-blue-600', 'border-blue-600');
        event.target.classList.remove('text-gray-600');

        // Reload notifications with filter
        loadNotifications();
    }

    // Mark notification as read
    function markAsRead(id) {
        fetch(`<?= base_url() ?>/notifications/mark-read/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const item = document.querySelector(`[data-id="${id}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        item.querySelector('button[onclick*="markAsRead"]')?.remove();
                    }
                    // Refresh counts
                    loadNotificationCounts();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
    }

    // Mark all notifications as read
    function markAllAsRead() {
        fetch(`<?= base_url() ?>/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadNotificationCounts();
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
    }

    // Delete notification
    function deleteNotification(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch(`<?= base_url() ?>/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from UI
                        const item = document.querySelector(`[data-id="${id}"]`);
                        if (item) {
                            item.remove();
                        }
                        // Refresh counts
                        loadNotificationCounts();
                    }
                })
                .catch(error => console.error('Error deleting notification:', error));
        }
    }

    // Refresh notifications
    function refreshNotifications() {
        loadNotifications();
    }

    // Force refresh notifications (for testing)
    function forceRefreshNotifications() {
        loadNotifications();
    }

    // Load notification counts (for badge)
    function loadNotificationCounts() {
        fetch(`<?= base_url() ?>/notifications/counts`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(data.unread_count);
                }
            })
            .catch(error => console.error('Error loading notification counts:', error));
    }

    // Show error message
    function showError(message) {
        const container = document.getElementById('notificationsList');
        container.innerHTML = `
        <div class="p-4 text-center text-red-500">
            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
            <p class="mt-2 font-medium">${message}</p>
            <div class="mt-3 space-y-2">
                <button onclick="loadNotifications()" class="text-sm text-blue-600 hover:text-blue-800 underline">
                    Try Again
                </button>
                <br>
                <a href="<?= base_url(session()->get('is_admin') ? 'admin/notifications' : 'notifications') ?>" 
                   class="text-sm text-green-600 hover:text-green-800 underline">
                    Go to Notifications Page
                </a>
            </div>
        </div>
    `;
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('notificationPanel').classList.add('hidden');
            notificationDropdownOpen = false;
        }
    });

    // Load initial notification counts
    document.addEventListener('DOMContentLoaded', function() {
        loadNotificationCounts();

        // Refresh counts every 30 seconds
        setInterval(loadNotificationCounts, 30000);
    });
</script>