<?php

/**
 * Reusable Pagination Component
 * 
 * Usage:
 * <?= view('components/pagination', [
 *     'pager' => $pager,
 *     'base_url' => base_url('admin/notifications'),
 *     'current_params' => $_GET
 * ]) ?>
 */
?>

<?php if (isset($pager) && $pager['total_pages'] > 1): ?>

    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <!-- Results Info -->
            <div class="text-sm text-gray-700">
                Showing <?= (($pager['current_page'] - 1) * $pager['per_page']) + 1 ?>
                to <?= min($pager['current_page'] * $pager['per_page'], $pager['total_items']) ?>
                of <?= $pager['total_items'] ?> results
            </div>

            <!-- Pagination Controls -->
            <div class="flex items-center space-x-2">
                <!-- Previous Button -->
                <?php if ($pager['has_previous']): ?>
                    <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $pager['previous_page']])) ?>"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left mr-1"></i>
                        Previous
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                // Show page numbers with ellipsis for better UX
                $total_pages = $pager['total_pages'];
                $current_page = $pager['current_page'];

                if ($total_pages <= 7) {
                    // If 7 or fewer pages, show all page numbers
                    for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $i])) ?>"
                            class="px-3 py-2 text-sm font-medium <?= $i === $current_page ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                            <?= $i ?>
                        </a>
                        <?php endfor;
                } else {
                    // If more than 7 pages, show smart pagination
                    if ($current_page <= 4) {
                        // Show first 5 pages + ellipsis + last page
                        for ($i = 1; $i <= 5; $i++): ?>
                            <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $i])) ?>"
                                class="px-3 py-2 text-sm font-medium <?= $i === $current_page ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <span class="px-2 text-gray-400">...</span>
                        <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $total_pages])) ?>"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <?= $total_pages ?>
                        </a>
                    <?php } elseif ($current_page >= $total_pages - 3) {
                        // Show first page + ellipsis + last 5 pages
                    ?>
                        <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => 1])) ?>"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            1
                        </a>
                        <span class="px-2 text-gray-400">...</span>
                        <?php for ($i = $total_pages - 4; $i <= $total_pages; $i++): ?>
                            <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $i])) ?>"
                                class="px-3 py-2 text-sm font-medium <?= $i === $current_page ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor;
                    } else {
                        // Show first page + ellipsis + current page range + ellipsis + last page
                        ?>
                        <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => 1])) ?>"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            1
                        </a>
                        <span class="px-2 text-gray-400">...</span>
                        <?php for ($i = $current_page - 1; $i <= $current_page + 1; $i++): ?>
                            <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $i])) ?>"
                                class="px-3 py-2 text-sm font-medium <?= $i === $current_page ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <span class="px-2 text-gray-400">...</span>
                        <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $total_pages])) ?>"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <?= $total_pages ?>
                        </a>
                <?php }
                } ?>

                <!-- Next Button -->
                <?php if ($pager['has_next']): ?>
                    <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['page' => $pager['next_page']])) ?>"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Next
                        <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Page Size Selector (Optional) -->
        <?php if (isset($show_page_size) && $show_page_size): ?>
            <div class="mt-3 flex items-center justify-center space-x-2">
                <span class="text-sm text-gray-600">Show:</span>
                <?php
                $page_sizes = [10, 20, 50, 100];
                foreach ($page_sizes as $size):
                    $is_current = ($pager['per_page'] == $size);
                ?>
                    <a href="<?= $base_url . '?' . http_build_query(array_merge($current_params, ['per_page' => $size, 'page' => 1])) ?>"
                        class="px-2 py-1 text-xs font-medium <?= $is_current ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50' ?> rounded transition-colors">
                        <?= $size ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>