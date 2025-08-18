<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🏆 Prize Claim Management</h1>
        <p class="text-gray-600">Review and approve pending prize claims from winners</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Claims</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($pending_claims) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved Claims</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($approved_claims) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pending Value</p>
                    <p class="text-2xl font-bold text-gray-900">Rs. <?= number_format($total_pending_value, 2) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Winners</p>
                    <p class="text-2xl font-bold text-gray-900"><?= count($all_winners) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Claims Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Pending Claims</h2>
            <p class="text-gray-600">Review and approve these prize claims</p>
        </div>
        
        <div class="p-6">
            <?php if (empty($pending_claims)): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Pending Claims</h3>
                    <p class="text-gray-500">All prize claims have been processed!</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($pending_claims as $claim): ?>
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-crown text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">
                                                <?= esc($claim['full_name']) ?> (@<?= esc($claim['username']) ?>)
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                <?= getOrdinal($claim['position']) ?> Place - <?= esc($claim['draw_title']) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Prize Details</h4>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Type:</span>
                                                    <span class="font-medium"><?= ucfirst($claim['draw_type']) ?> Draw</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Amount:</span>
                                                    <span class="font-bold text-green-600">Rs. <?= number_format($claim['prize_amount'], 2) ?></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Won On:</span>
                                                    <span class="font-medium"><?= date('M d, Y', strtotime($claim['created_at'])) ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Claim Details</h4>
                                            <?php 
                                            $claimDetails = json_decode($claim['claim_details'], true);
                                            ?>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">WhatsApp:</span>
                                                    <span class="font-medium"><?= esc($claimDetails['whatsapp'] ?? 'N/A') ?></span>
                                                </div>
                                                <?php if (!empty($claimDetails['phone'])): ?>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Phone:</span>
                                                    <span class="font-medium"><?= esc($claimDetails['phone']) ?></span>
                                                </div>
                                                <?php endif; ?>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Address:</span>
                                                    <span class="font-medium text-xs"><?= esc(substr($claimDetails['address'] ?? 'N/A', 0, 50)) ?>...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($claimDetails['additional_info'])): ?>
                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-2">Additional Information</h4>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                            <?= esc($claimDetails['additional_info']) ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>

                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><i class="fas fa-clock mr-1"></i>Claimed: <?= date('M d, Y g:i A', strtotime($claim['claimed_at'] ?? $claim['updated_at'])) ?></span>
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>IP: <?= esc($claimDetails['ip_address'] ?? 'N/A') ?></span>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-3 ml-6">
                                    <button onclick="approveClaim(<?= $claim['id'] ?>)" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-check mr-2"></i>Approve
                                    </button>
                                    
                                    <button onclick="rejectClaim(<?= $claim['id'] ?>)" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-times mr-2"></i>Reject
                                    </button>
                                    
                                    <button onclick="viewFullDetails(<?= $claim['id'] ?>)" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- All Winners Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">All Winners</h2>
            <p class="text-gray-600">Complete list of all winners and their status</p>
        </div>
        
        <div class="p-6">
            <?php if (empty($all_winners)): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No Winners Yet</h3>
                    <p class="text-gray-500">Winners will appear here after draws are completed</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Winner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Draw</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prize</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($all_winners as $winner): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-crown text-white text-xs"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900"><?= esc($winner['full_name']) ?></div>
                                                <div class="text-sm text-gray-500">@<?= esc($winner['username']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= esc($winner['draw_title']) ?></div>
                                        <div class="text-sm text-gray-500"><?= getOrdinal($winner['position']) ?> Place</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-green-600">Rs. <?= number_format($winner['prize_amount'], 2) ?></div>
                                        <div class="text-sm text-gray-500"><?= ucfirst($winner['draw_type']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($winner['is_claimed']): ?>
                                            <?php if ($winner['claim_approved']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-gift mr-1"></i>Not Claimed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?= base_url('admin/winners/view/' . $winner['id']) ?>" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <?php if ($winner['is_claimed'] && !$winner['claim_approved']): ?>
                                            <button onclick="approveClaim(<?= $winner['id'] ?>)" 
                                                    class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button onclick="rejectClaim(<?= $winner['id'] ?>)" 
                                                    class="text-red-600 hover:text-red-900">Reject</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Claim Details Modal -->
<div id="claimModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Claim Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="text-sm text-gray-600">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function approveClaim(winnerId) {
    if (confirm('Are you sure you want to approve this claim?')) {
        window.location.href = '<?= base_url('admin/winners/approve/') ?>' + winnerId;
    }
}

function rejectClaim(winnerId) {
    if (confirm('Are you sure you want to reject this claim?')) {
        window.location.href = '<?= base_url('admin/winners/reject/') ?>' + winnerId;
    }
}

function viewFullDetails(winnerId) {
    // Load claim details via AJAX and show in modal
    fetch('<?= base_url('admin/winners/details/') ?>' + winnerId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalContent').innerHTML = data.html;
                document.getElementById('claimModal').classList.remove('hidden');
            }
        });
}

function closeModal() {
    document.getElementById('claimModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('claimModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php
// Helper function to get ordinal suffix
function getOrdinal($n) {
    if ($n >= 11 && $n <= 13) {
        return $n . 'th';
    }
    switch ($n % 10) {
        case 1:  return $n . 'st';
        case 2:  return $n . 'nd';
        case 3:  return $n . 'rd';
        default: return $n . 'th';
    }
}
?>

<?= $this->endSection() ?>
