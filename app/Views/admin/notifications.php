<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Success/Error Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications Center</h2>
                <p class="text-gray-600 mt-1">Monitor user activities and system notifications</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('notifications/mark-all-read') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check-double mr-2"></i>
                    Mark All Read
                </a>
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
                    <p class="text-2xl font-bold text-gray-900" data-stat="total"><?= $stats['total'] ?? 0 ?></p>
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
                    <p class="text-2xl font-bold text-gray-900" data-stat="unread"><?= $stats['unread'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">High Priority</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['high_priority'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                        <i class="fas fa-clock text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Today</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count(array_filter($notifications, function ($n) {
                                                                    return date('Y-m-d', strtotime($n['created_at'])) === date('Y-m-d');
                                                                })) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex space-x-4">
                <a href="<?= base_url('admin/notifications?filter=all') ?>" class="filter-btn <?= $current_filter === 'all' ? 'active px-4 py-2 rounded-lg bg-blue-600 text-white' : 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    All
                </a>
                <a href="<?= base_url('admin/notifications?filter=unread') ?>" class="filter-btn <?= $current_filter === 'unread' ? 'active px-4 py-2 rounded-lg bg-blue-600 text-white' : 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    Unread
                </a>
                <a href="<?= base_url('admin/notifications?filter=user_topup') ?>" class="filter-btn <?= $current_filter === 'user_topup' ? 'active px-4 py-2 rounded-lg bg-blue-600 text-white' : 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    Deposits
                </a>
                <a href="<?= base_url('admin/notifications?filter=user_withdraw') ?>" class="filter-btn <?= $current_filter === 'user_withdraw' ? 'active px-4 py-2 rounded-lg bg-blue-600 text-white' : 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    Withdrawals
                </a>
                <a href="<?= base_url('admin/notifications?filter=draw_participation') ?>" class="filter-btn <?= $current_filter === 'draw_participation' ? 'active px-4 py-2 rounded-lg bg-blue-600 text-white' : 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    Draw Entries
                </a>
            </div>
            <div class="flex items-center space-x-2">
                <form method="GET" action="<?= base_url('admin/notifications') ?>" class="flex items-center space-x-2">
                    <input type="hidden" name="filter" value="<?= $current_filter ?>">
                    <input type="date" name="date" value="<?= $date_filter ?>" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <a href="<?= base_url('admin/notifications') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Notifications</h3>
        </div>
        <div id="notificationsList" class="divide-y divide-gray-200">
            <?php if (empty($notifications)): ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-3xl mb-2"></i>
                    <p>No notifications found</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="p-6 <?= $notification['is_read'] ? '' : 'bg-blue-50' ?>" data-id="<?= $notification['id'] ?>">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full <?= $notification['style']['color'] === 'green' ? 'bg-green-100' : ($notification['style']['color'] === 'orange' ? 'bg-orange-100' : ($notification['style']['color'] === 'blue' ? 'bg-blue-100' : ($notification['style']['color'] === 'red' ? 'bg-red-100' : ($notification['style']['color'] === 'yellow' ? 'bg-yellow-100' : ($notification['style']['color'] === 'purple' ? 'bg-purple-100' : 'bg-gray-100'))))) ?> flex items-center justify-center">
                                    <i class="fas <?= $notification['style']['icon'] ?> <?= $notification['style']['color'] === 'green' ? 'text-green-600' : ($notification['style']['color'] === 'orange' ? 'text-orange-600' : ($notification['style']['color'] === 'blue' ? 'text-blue-600' : ($notification['style']['color'] === 'red' ? 'text-red-600' : ($notification['style']['color'] === 'yellow' ? 'text-yellow-600' : ($notification['style']['color'] === 'purple' ? 'text-purple-600' : 'text-gray-600'))))) ?>"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($notification['title']) ?></h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $notification['priority'] === 'low' ? 'bg-gray-100 text-gray-800' : ($notification['priority'] === 'medium' ? 'bg-blue-100 text-blue-800' : ($notification['priority'] === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) ?>">
                                            <?= ucfirst($notification['priority']) ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500"><?= $notification['time_ago'] ?></span>
                                        <?php if (!$notification['is_read']): ?>
                                            <button onclick="markAsRead(<?= $notification['id'] ?>)" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Mark as read
                                            </button>
                                        <?php endif; ?>
                                        <button onclick="deleteNotification(<?= $notification['id'] ?>)" class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($notification['message']) ?></p>
                                <?php if ($notification['username']): ?>
                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        <span><?= htmlspecialchars($notification['full_name'] ?: $notification['username']) ?> (<?= htmlspecialchars($notification['email']) ?>)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager['total_pages'] > 1): ?>
            <?= view('components/pagination', [
                'pager' => $pager,
                'base_url' => base_url('admin/notifications'),
                'current_params' => $_GET,
                'show_page_size' => true
            ]) ?>
        <?php else: ?>
            <div class="px-6 py-4 border-t border-gray-200 text-center text-gray-500">
                <?php if (isset($pager)): ?>
                    Showing all <?= $pager['total_items'] ?? 0 ?> notifications (no pagination needed)
                <?php else: ?>
                    Pagination data not available
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
    // Mark notification as read
    function markAsRead(id) {
        const button = event.target;
        const originalText = button.textContent;

        // Show loading state
        button.disabled = true;
        button.textContent = 'Marking...';

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
                    // Update the UI
                    const notificationItem = document.querySelector(`[data-id="${id}"]`);
                    if (notificationItem) {
                        // Remove blue background
                        notificationItem.classList.remove('bg-blue-50');
                        // Remove the mark as read button
                        button.remove();
                    }
                    // Update stats
                    updateStats();
                    // Show success message
                    showMessage('Notification marked as read successfully!', 'success');
                } else {
                    showMessage('Failed to mark as read: ' + data.message, 'error');
                    // Reset button
                    button.disabled = false;
                    button.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error marking as read:', error);
                showMessage('Error marking notification as read', 'error');
                // Reset button
                button.disabled = false;
                button.textContent = originalText;
            });
    }

    // Delete notification
    function deleteNotification(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            const button = event.target;
            const originalText = button.textContent;

            // Show loading state
            button.disabled = true;
            button.textContent = 'Deleting...';

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
                        // Remove the notification from UI
                        const notificationItem = document.querySelector(`[data-id="${id}"]`);
                        if (notificationItem) {
                            notificationItem.remove();
                        }
                        // Update stats
                        updateStats();
                        // Show success message
                        showMessage('Notification deleted successfully!', 'success');
                    } else {
                        showMessage('Failed to delete: ' + data.message, 'error');
                        // Reset button
                        button.disabled = false;
                        button.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error deleting notification:', error);
                    showMessage('Error deleting notification', 'error');
                    // Reset button
                    button.disabled = false;
                    button.textContent = originalText;
                });
        }
    }

    // Show message to user
    function showMessage(message, type = 'success') {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.message-toast');
        existingMessages.forEach(msg => msg.remove());

        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(messageDiv);

        // Animate in
        setTimeout(() => {
            messageDiv.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 3 seconds
        setTimeout(() => {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 300);
        }, 3000);
    }

    // Update stats after actions
    function updateStats() {
        // Count remaining notifications
        const totalNotifications = document.querySelectorAll('[data-id]').length;
        const unreadNotifications = document.querySelectorAll('.bg-blue-50').length;

        // Update the stats display
        document.querySelector('[data-stat="total"]').textContent = totalNotifications;
        document.querySelector('[data-stat="unread"]').textContent = unreadNotifications;
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
                    location.reload();
                } else {
                    alert('Failed to send broadcast: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error sending broadcast:', error);
                alert('Error sending broadcast');
            });
    }
</script>

<?= $this->endSection() ?>