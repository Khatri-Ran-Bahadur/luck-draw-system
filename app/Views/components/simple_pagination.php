<?php
/**
 * Simple Pagination Component
 * 
 * Usage:
 * <?= view('components/simple_pagination', [
 *     'pager' => $pager,
 *     'base_url' => base_url('admin/users')
 * ]) ?>
 */
?>

<?php if (isset($pager) && $pager['total_pages'] > 1): ?>
    <div class="flex items-center justify-center space-x-2 py-4">
        <!-- Previous Button -->
        <?php if ($pager['has_previous']): ?>
            <a href="<?= $base_url . '?page=' . $pager['previous_page'] ?>" 
               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-chevron-left mr-1"></i>
                Previous
            </a>
        <?php endif; ?>
        
        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $pager['total_pages']; $i++): ?>
            <a href="<?= $base_url . '?page=' . $i ?>" 
               class="px-3 py-2 text-sm font-medium <?= $i === $pager['current_page'] ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg">
                <?= $i ?>
            </a>
        <?php endfor; ?>
        
        <!-- Next Button -->
        <?php if ($pager['has_next']): ?>
            <a href="<?= $base_url . '?page=' . $pager['next_page'] ?>" 
               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Next
                <i class="fas fa-chevron-right ml-1"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>
