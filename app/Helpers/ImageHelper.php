<?php

if (!function_exists('get_product_image')) {
    /**
     * Get product image with fallback
     * 
     * @param string|null $image_path
     * @param string $fallback_class
     * @param string $fallback_icon
     * @return string
     */
    function get_product_image($image_path, $fallback_class = 'w-full h-full object-cover', $fallback_icon = 'fas fa-gift') {
        if (!empty($image_path) && file_exists(FCPATH . $image_path)) {
            return '<img src="' . base_url($image_path) . '" alt="Product" class="' . $fallback_class . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">';
        }
        
        return '<div class="' . $fallback_class . ' bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center"><i class="' . $fallback_icon . ' text-4xl text-purple-300"></i></div>';
    }
}

if (!function_exists('get_product_image_src')) {
    /**
     * Get product image source with validation
     * 
     * @param string|null $image_path
     * @return string|null
     */
    function get_product_image_src($image_path) {
        if (!empty($image_path) && file_exists(FCPATH . $image_path)) {
            return base_url($image_path);
        }
        return null;
    }
}

if (!function_exists('get_profile_image')) {
    /**
     * Get profile image with fallback
     * 
     * @param string|null $image_path
     * @param string $fallback_class
     * @param string $fallback_icon
     * @return string
     */
    function get_profile_image($image_path, $fallback_class = 'w-full h-full object-cover', $fallback_icon = 'fas fa-user') {
        if (!empty($image_path) && file_exists(FCPATH . $image_path)) {
            return '<img src="' . base_url($image_path) . '" alt="Profile" class="' . $fallback_class . '" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">';
        }
        
        return '<div class="' . $fallback_class . ' bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center"><i class="' . $fallback_icon . ' text-4xl text-blue-300"></i></div>';
    }
}

if (!function_exists('get_profile_image_src')) {
    /**
     * Get profile image source with validation
     * 
     * @param string|null $image_path
     * @return string|null
     */
    function get_profile_image_src($image_path) {
        if (!empty($image_path) && file_exists(FCPATH . $image_path)) {
            return base_url($image_path);
        }
        return null;
    }
}
