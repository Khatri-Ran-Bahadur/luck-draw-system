<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications Center</h2>
                <p class="text-gray-600 mt-1">Monitor user activities and system notifications</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="markAllAsRead()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check-double mr-2"></i>
                    Mark All Read
                </button>
                <button onclick="openBroadcastModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-bullhorn mr-2"></i>
                    Broadcast Message
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bell text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Notifications</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalNotifications">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unread</p>
                    <p class="text-2xl font-bold text-gray-900" id="unreadNotifications">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">High Priority</p>
                    <p class="text-2xl font-bold text-gray-900" id="highPriorityNotifications">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today</p>
                    <p class="text-2xl font-bold text-gray-900" id="todayNotifications">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex space-x-4">
                <button onclick="filterNotifications('all')" class="filter-btn active px-4 py-2 rounded-lg bg-blue-600 text-white">
                    All
                </button>
                <button onclick="filterNotifications('unread')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Unread
                </button>
                <button onclick="filterNotifications('user_topup')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Deposits
                </button>
                <button onclick="filterNotifications('user_withdraw')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Withdrawals
                </button>
                <button onclick="filterNotifications('draw_participation')" class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Draw Entries
                </button>
            </div>
            <div class="flex items-center space-x-2">
                <input type="date" id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg">
                <button onclick="refreshNotifications()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Notifications</h3>
        </div>
        <div id="notificationsList" class="divide-y divide-gray-200">
            <!-- Loading State -->
            <div id="loadingState" class="p-8 text-center">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                <p class="mt-2 text-gray-500">Loading notifications...</p>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="px-6 py-4 border-t border-gray-200 text-center">
            <button id="loadMoreBtn" onclick="loadMoreNotifications()" class="text-blue-600 hover:text-blue-800 font-medium hidden">
                Load More Notifications
            </button>
        </div>
    </div>
</div>

<!-- Broadcast Message Modal -->
<div id="broadcastModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Broadcast Message</h3>
                <button onclick="closeBroadcastModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="broadcastForm" onsubmit="sendBroadcast(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expires After (hours)</label>
                        <input type="number" name="expires_hours" min="1" max="168" placeholder="Optional" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeBroadcastModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Send Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentFilter = 'all';
    let currentPage = 1;
    let notifications = [];

    // Load notifications
    function loadNotifications(reset = true) {
        if (reset) {
            currentPage = 1;
            notifications = [];
        }

        const params = new URLSearchParams({
            unread_only: currentFilter === 'unread' ? 'true' : 'false',
            type: currentFilter !== 'all' && currentFilter !== 'unread' ? currentFilter : '',
            limit: 20,
            page: currentPage
        });

        const dateFilter = document.getElementById('dateFilter').value;
        if (dateFilter) {
            params.append('date_from', dateFilter);
            params.append('date_to', dateFilter);
        }

        fetch(`<?= base_url('notifications/admin') ?>?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (reset) {
                        notifications = data.notifications;
                    } else {
                        notifications = [...notifications, ...data.notifications];
                    }
                    renderNotifications();
                    updateStats();

                    // Show/hide load more button
                    const loadMoreBtn = document.getElementById('loadMoreBtn');
                    if (data.notifications.length === 20) {
                        loadMoreBtn.classList.remove('hidden');
                    } else {
                        loadMoreBtn.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            })
            .finally(() => {
                document.getElementById('loadingState').classList.add('hidden');
            });
    }

    // Render notifications
    function renderNotifications() {
        const container = document.getElementById('notificationsList');
        const loadingState = document.getElementById('loadingState');

        if (notifications.length === 0) {
            container.innerHTML = `
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-3xl mb-2"></i>
                <p>No notifications found</p>
            </div>
        `;
            return;
        }

        const html = notifications.map(notification => `
        <div class="p-6 ${notification.is_read ? '' : 'bg-blue-50'}" data-id="${notification.id}">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-${notification.style.color}-100 flex items-center justify-center">
                        <i class="fas ${notification.style.icon} text-${notification.style.color}-600"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <h4 class="text-sm font-medium text-gray-900">${notification.title}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${notification.priority_style.class}">
                                ${notification.priority_style.text}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">${notification.time_ago}</span>
                            ${!notification.is_read ? `
                                <button onclick="markAsRead(${notification.id})" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Mark as read
                                </button>
                            ` : ''}
                            <button onclick="deleteNotification(${notification.id})" class="text-red-600 hover:text-red-800 text-sm">
                                Delete
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                    ${notification.username ? `
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <i class="fas fa-user mr-1"></i>
                            <span>${notification.full_name || notification.username} (${notification.email})</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');

        container.innerHTML = html;
    }

    // Filter notifications
    function filterNotifications(filter) {
        currentFilter = filter;

        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });

        event.target.classList.add('active', 'bg-blue-600', 'text-white');
        event.target.classList.remove('bg-gray-200', 'text-gray-700');

        document.getElementById('loadingState').classList.remove('hidden');
        loadNotifications();
    }

    // Load more notifications
    function loadMoreNotifications() {
        currentPage++;
        loadNotifications(false);
    }

    // Mark as read
    function markAsRead(id) {
        fetch(`<?= base_url('notifications/mark-read') ?>/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-id="${id}"]`);
                    if (item) {
                        item.classList.remove('bg-blue-50');
                        item.querySelector('button[onclick*="markAsRead"]')?.remove();
                    }
                    updateStats();
                }
            });
    }

    // Mark all as read
    function markAllAsRead() {
        fetch(`<?= base_url('notifications/mark-all-read') ?>`, {
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
                }
            });
    }

    // Delete notification
    function deleteNotification(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch(`<?= base_url('notifications') ?>/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.querySelector(`[data-id="${id}"]`);
                        if (item) {
                            item.remove();
                        }
                        updateStats();
                    }
                });
        }
    }

    // Refresh notifications
    function refreshNotifications() {
        document.getElementById('loadingState').classList.remove('hidden');
        loadNotifications();
    }

    // Update stats
    function updateStats() {
        // This would typically come from the API
        // For now, we'll calculate from current notifications
        const total = notifications.length;
        const unread = notifications.filter(n => !n.is_read).length;
        const highPriority = notifications.filter(n => n.priority === 'high' || n.priority === 'urgent').length;
        const today = notifications.filter(n => {
            const notificationDate = new Date(n.created_at).toDateString();
            const todayDate = new Date().toDateString();
            return notificationDate === todayDate;
        }).length;

        document.getElementById('totalNotifications').textContent = total;
        document.getElementById('unreadNotifications').textContent = unread;
        document.getElementById('highPriorityNotifications').textContent = highPriority;
        document.getElementById('todayNotifications').textContent = today;
    }

    // Broadcast modal functions
    function openBroadcastModal() {
        document.getElementById('broadcastModal').classList.remove('hidden');
    }

    function closeBroadcastModal() {
        document.getElementById('broadcastModal').classList.add('hidden');
        document.getElementById('broadcastForm').reset();
    }

    function sendBroadcast(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);

        fetch(`<?= base_url('notifications/broadcast') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeBroadcastModal();
                    alert('Message broadcasted successfully!');
                } else {
                    alert('Failed to send broadcast: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error sending broadcast:', error);
                alert('Error sending broadcast');
            });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();

        // Date filter change
        document.getElementById('dateFilter').addEventListener('change', function() {
            document.getElementById('loadingState').classList.remove('hidden');
            loadNotifications();
        });
    });
</script>

<?= $this->endSection() ?>