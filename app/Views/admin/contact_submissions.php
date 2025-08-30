<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Contact Submissions</h2>
                <p class="text-gray-600 mt-1">Manage customer inquiries and support requests</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    Total: <?= $stats['total'] ?> | Pending: <?= $stats['pending'] ?> | Replied: <?= $stats['replied'] ?> | Closed: <?= $stats['closed'] ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Submissions</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['pending'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-reply text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Replied</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['replied'] ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Closed</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['closed'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Submissions -->
    <?php if (!empty($pending_submissions)): ?>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pending Submissions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pending_submissions as $submission): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= esc($submission['name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= esc($submission['email']) ?></div>
                                        <?php if (!empty($submission['phone'])): ?>
                                            <div class="text-sm text-gray-500"><?= esc($submission['phone']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate"><?= esc($submission['subject']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md truncate"><?= esc($submission['message']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y g:i A', strtotime($submission['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewSubmission(<?= $submission['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="replyToSubmission(<?= $submission['id'] ?>)" class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>
                                    <button onclick="closeSubmission(<?= $submission['id'] ?>)" class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-times"></i> Close
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Processed Submissions -->
    <?php if (!empty($processed_submissions)): ?>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Processed Submissions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($processed_submissions as $submission): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= esc($submission['name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= esc($submission['email']) ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate"><?= esc($submission['subject']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                <?= $submission['status'] === 'replied' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= ucfirst($submission['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y g:i A', strtotime($submission['updated_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewSubmission(<?= $submission['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="deleteSubmission(<?= $submission['id'] ?>)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($pending_submissions) && empty($processed_submissions)): ?>
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Contact Submissions</h3>
            <p class="text-gray-500">There are no contact submissions to display at the moment.</p>
        </div>
    <?php endif; ?>
</div>

<!-- View Submission Modal -->
<div id="viewSubmissionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Contact Submission Details</h3>
            </div>
            <div id="submissionDetails" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <form id="replyForm" method="POST" action="<?= base_url('admin/update-contact-submission') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="replySubmissionId" name="id">
                <input type="hidden" name="status" value="replied">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Reply to Submission</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Reply Message</label>
                        <textarea id="admin_notes" name="admin_notes" rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your reply message..." required></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('replyModal')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function viewSubmission(id) {
        // In a real implementation, you would fetch the submission details via AJAX
        // For now, we'll just show the modal
        document.getElementById('viewSubmissionModal').classList.remove('hidden');
    }

    function replyToSubmission(id) {
        document.getElementById('replySubmissionId').value = id;
        document.getElementById('replyModal').classList.remove('hidden');
    }

    function closeSubmission(id) {
        if (confirm('Are you sure you want to close this submission?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('admin/update-contact-submission') ?>';

            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '<?= csrf_token() ?>';
            csrfField.value = '<?= csrf_hash() ?>';

            const idField = document.createElement('input');
            idField.type = 'hidden';
            idField.name = 'id';
            idField.value = id;

            const statusField = document.createElement('input');
            statusField.type = 'hidden';
            statusField.name = 'status';
            statusField.value = 'closed';

            form.appendChild(csrfField);
            form.appendChild(idField);
            form.appendChild(statusField);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteSubmission(id) {
        if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
            window.location.href = '<?= base_url('admin/delete-contact-submission/') ?>' + id;
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const modals = ['viewSubmissionModal', 'replyModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    });
</script>

<?= $this->endSection() ?>