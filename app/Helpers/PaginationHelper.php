<?php

namespace App\Helpers;

class PaginationHelper
{
    /**
     * Generate pagination data
     *
     * @param int $totalItems Total number of items
     * @param int $currentPage Current page number
     * @param int $perPage Items per page
     * @return array Pagination data
     */
    public static function generatePager($totalItems, $currentPage = 1, $perPage = 20)
    {
        $totalPages = ceil($totalItems / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        return [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'per_page' => $perPage,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null,
            'start_item' => (($currentPage - 1) * $perPage) + 1,
            'end_item' => min($currentPage * $perPage, $totalItems)
        ];
    }
    
    /**
     * Get offset for database queries
     *
     * @param int $page Current page
     * @param int $perPage Items per page
     * @return int Offset value
     */
    public static function getOffset($page, $perPage = 20)
    {
        return ($page - 1) * $perPage;
    }
    
    /**
     * Validate and sanitize pagination parameters
     *
     * @param int $page Requested page
     * @param int $perPage Requested items per page
     * @param int $maxPerPage Maximum allowed items per page
     * @return array Validated page and perPage values
     */
    public static function validateParams($page, $perPage = 20, $maxPerPage = 100)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, min((int) $perPage, $maxPerPage));
        
        return [$page, $perPage];
    }
    
    /**
     * Generate pagination links for a specific page range
     *
     * @param array $pager Pagination data
     * @param string $baseUrl Base URL for pagination links
     * @param array $params Additional URL parameters
     * @param int $range Number of pages to show around current page
     * @return array Array of page links
     */
    public static function generatePageLinks($pager, $baseUrl, $params = [], $range = 2)
    {
        $links = [];
        $startPage = max(1, $pager['current_page'] - $range);
        $endPage = min($pager['total_pages'], $pager['current_page'] + $range);
        
        // First page
        if ($startPage > 1) {
            $links[] = [
                'page' => 1,
                'text' => '1',
                'is_current' => false,
                'url' => $baseUrl . '?' . http_build_query(array_merge($params, ['page' => 1]))
            ];
            
            if ($startPage > 2) {
                $links[] = [
                    'page' => null,
                    'text' => '...',
                    'is_current' => false,
                    'is_separator' => true
                ];
            }
        }
        
        // Page range
        for ($i = $startPage; $i <= $endPage; $i++) {
            $links[] = [
                'page' => $i,
                'text' => (string) $i,
                'is_current' => $i === $pager['current_page'],
                'url' => $baseUrl . '?' . http_build_query(array_merge($params, ['page' => $i]))
            ];
        }
        
        // Last page
        if ($endPage < $pager['total_pages']) {
            if ($endPage < $pager['total_pages'] - 1) {
                $links[] = [
                    'page' => null,
                    'text' => '...',
                    'is_current' => false,
                    'is_separator' => true
                ];
            }
            
            $links[] = [
                'page' => $pager['total_pages'],
                'text' => (string) $pager['total_pages'],
                'is_current' => false,
                'url' => $baseUrl . '?' . http_build_query(array_merge($params, ['page' => $pager['total_pages']]))
            ];
        }
        
        return $links;
    }
}
