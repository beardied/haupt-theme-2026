<?php
/**
 * Template Functions
 * Helper functions for templates
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Custom Walker for Navigation Menu
 */
class Haupt_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Add last-item class to the final top-level menu item.
     */
    public function walk($elements, $max_depth, ...$args) {
        // Find the last top-level item and mark it
        $last_top_index = null;
        foreach ($elements as $index => $element) {
            if ($element->menu_item_parent == 0) {
                $last_top_index = $index;
            }
        }
        if ($last_top_index !== null) {
            $elements[$last_top_index]->classes[] = 'last-item';
        }
        return parent::walk($elements, $max_depth, ...$args);
    }
    
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts = [];
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before ?? '';
        $item_output .= $title;
        $item_output .= $args->link_after ?? '';
        
        // Add dropdown arrow for items with children
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= ' <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Check if a nav menu location has actual items (not just assigned)
 */
function haupt_nav_menu_has_items($location) {
    if (!has_nav_menu($location)) {
        return false;
    }
    $locations = get_nav_menu_locations();
    if (!isset($locations[$location])) {
        return false;
    }
    $items = wp_get_nav_menu_items($locations[$location]);
    return !empty($items);
}

/**
 * Get SVG icon
 */
function haupt_get_icon($name, $size = 24, $class = '') {
    $icons = [
        'search' => '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>',
        'menu' => '<line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>',
        'close' => '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>',
        'chevron-down' => '<polyline points="6 9 12 15 18 9"></polyline>',
        'chevron-right' => '<polyline points="9 18 15 12 9 6"></polyline>',
        'chevron-left' => '<polyline points="15 18 9 12 15 6"></polyline>',
        'arrow-right' => '<line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>',
        'arrow-left' => '<line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>',
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
        'email' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
        'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
        'clock' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
        'check' => '<polyline points="20 6 9 17 4 12"></polyline>',
        'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
        'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>',
        'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
        'zap' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>',
        'wind' => '<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path>',
        'anchor' => '<circle cx="12" cy="5" r="3"></circle><line x1="12" y1="22" x2="12" y2="8"></line><path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>',
        'cpu' => '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line>',
        'sun' => '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>',
        'droplet' => '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>',
    ];
    
    if (!isset($icons[$name])) {
        return '';
    }
    
    $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';
    
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' . $class_attr . '>' . $icons[$name] . '</svg>';
}

/**
 * Get reading time for a post
 */
function haupt_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    return sprintf(_n('%d min read', '%d min read', $reading_time, 'haupt-recruitment'), $reading_time);
}

/**
 * Truncate text to a specific length
 */
function haupt_truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get related posts
 */
function haupt_get_related_posts($post_id = null, $count = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return [];
    }
    
    $category_ids = array_map(function($cat) {
        return $cat->term_id;
    }, $categories);
    
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post__not_in' => [$post_id],
        'category__in' => $category_ids,
        'orderby' => 'rand',
    ];
    
    return get_posts($args);
}

// haupt_has_transparent_header() function is defined in theme-options.php

/**
 * Get page title with fallback
 */
function haupt_get_page_title() {
    if (is_home()) {
        return get_the_title(get_option('page_for_posts'));
    }
    
    if (is_archive()) {
        return get_the_archive_title();
    }
    
    if (is_search()) {
        return sprintf(__('Search Results for: %s', 'haupt-recruitment'), get_search_query());
    }
    
    if (is_404()) {
        return __('Page Not Found', 'haupt-recruitment');
    }
    
    return get_the_title();
}

/**
 * Add custom image sizes to media library
 */
add_filter('image_size_names_choose', function($sizes) {
    return array_merge($sizes, [
        'hero' => __('Hero (1920x1080)', 'haupt-recruitment'),
        'card' => __('Card (600x400)', 'haupt-recruitment'),
        'thumbnail-wide' => __('Thumbnail Wide (400x225)', 'haupt-recruitment'),
    ]);
});

/**
 * Add responsive embed wrapper
 */
add_filter('embed_oembed_html', function($html, $url, $attr, $post_id) {
    return '<div class="embed-responsive">' . $html . '</div>';
}, 10, 4);

/**
 * Remove archive title prefixes
 */
add_filter('get_the_archive_title_prefix', '__return_empty_string');

/**
 * Get SEO Meta Title
 * Generates appropriate title tag for all page types
 */
function haupt_get_seo_title() {
    $site_name = get_bloginfo('name');
    $separator = ' | ';
    
    // Single job
    if (is_singular('job')) {
        $title = get_the_title();
        $company = get_post_meta(get_the_ID(), 'company_name', true);
        if ($company) {
            return $title . ' at ' . $company . $separator . $site_name;
        }
        return $title . $separator . 'Jobs' . $separator . $site_name;
    }
    
    // Career guide / role expertise
    if (is_singular('role_expertise')) {
        return get_the_title() . $separator . 'Career Guide' . $separator . $site_name;
    }
    
    // Single post
    if (is_singular('post')) {
        return get_the_title() . $separator . 'Blog' . $separator . $site_name;
    }
    
    // Single page
    if (is_singular('page')) {
        return get_the_title() . $separator . $site_name;
    }
    
    // Job archive
    if (is_post_type_archive('job')) {
        return 'Jobs' . $separator . $site_name;
    }
    
    // Career guide archive
    if (is_post_type_archive('role_expertise')) {
        return 'Career Guides' . $separator . $site_name;
    }
    
    // Job taxonomy archives
    if (is_tax('job_sector')) {
        return single_term_title('', false) . ' Jobs' . $separator . $site_name;
    }
    if (is_tax('job_location')) {
        return 'Jobs in ' . single_term_title('', false) . $separator . $site_name;
    }
    if (is_tax('job_type')) {
        return single_term_title('', false) . ' Jobs' . $separator . $site_name;
    }
    if (is_tax('job_category')) {
        return single_term_title('', false) . ' Jobs' . $separator . $site_name;
    }
    
    // Category archive
    if (is_category()) {
        return single_cat_title('', false) . $separator . 'Blog' . $separator . $site_name;
    }
    
    // Tag archive
    if (is_tag()) {
        return single_tag_title('', false) . $separator . 'Blog' . $separator . $site_name;
    }
    
    // Author archive
    if (is_author()) {
        return get_the_author() . $separator . 'Author' . $separator . $site_name;
    }
    
    // Search results
    if (is_search()) {
        return 'Search: ' . get_search_query() . $separator . $site_name;
    }
    
    // 404 page
    if (is_404()) {
        return 'Page Not Found' . $separator . $site_name;
    }
    
    // Blog archive
    if (is_home()) {
        return 'Blog' . $separator . $site_name;
    }
    
    // Date archives
    if (is_year()) {
        return get_the_date('Y') . $separator . 'Blog' . $separator . $site_name;
    }
    if (is_month()) {
        return get_the_date('F Y') . $separator . 'Blog' . $separator . $site_name;
    }
    if (is_day()) {
        return get_the_date('F j, Y') . $separator . 'Blog' . $separator . $site_name;
    }
    
    // Default
    return $site_name;
}

/**
 * Get SEO Meta Description
 * Generates appropriate meta description for all page types
 */
function haupt_get_seo_description() {
    // Homepage (check first as it can also be a page)
    if (is_front_page()) {
        return haupt_get_option('homepage_hero_subtitle', 'Specialist recruitment for maritime, offshore, renewables and construction. Connecting talented professionals with leading companies worldwide.');
    }
    
    // Try to get custom excerpt first for singular pages
    if (is_singular() && has_excerpt()) {
        return wp_strip_all_tags(get_the_excerpt(), true);
    }
    
    // Single job
    if (is_singular('job')) {
        $description = get_post_meta(get_the_ID(), 'short_description', true);
        if ($description) {
            return wp_strip_all_tags($description, true);
        }
        // Fall back to content excerpt
        return wp_trim_words(get_the_content(null, false, get_the_ID()), 25, '...');
    }
    
    // Career guide / role expertise
    if (is_singular('role_expertise')) {
        $description = get_post_meta(get_the_ID(), 'guide_excerpt', true);
        if ($description) {
            return wp_strip_all_tags($description, true);
        }
        return wp_trim_words(get_the_content(null, false, get_the_ID()), 25, '...');
    }
    
    // Single post or page
    if (is_singular()) {
        $content = get_the_content(null, false, get_the_ID());
        if (!empty($content)) {
            return wp_trim_words($content, 25, '...');
        }
    }
    
    // Job archive
    if (is_post_type_archive('job')) {
        return 'Browse ' . haupt_get_option('stats_live_jobs', '500+') . ' job vacancies across maritime, renewables, energy and construction sectors. Find your next career move with Haupt Recruitment.';
    }
    
    // Career guide archive
    if (is_post_type_archive('role_expertise')) {
        return 'Explore career guides for maritime, offshore, renewables and construction industries. Expert advice on qualifications, salaries and career progression.';
    }
    
    // Job taxonomy archives
    if (is_tax('job_sector')) {
        $term = get_queried_object();
        return 'Browse ' . $term->name . ' jobs. Find the latest vacancies and career opportunities in the ' . $term->name . ' sector with Haupt Recruitment.';
    }
    if (is_tax('job_location')) {
        $term = get_queried_object();
        return 'Find jobs in ' . $term->name . '. Browse the latest vacancies and career opportunities in ' . $term->name . ' with Haupt Recruitment.';
    }
    if (is_tax('job_type')) {
        $term = get_queried_object();
        return 'Browse ' . $term->name . ' jobs. Find flexible and permanent ' . $term->name . ' positions with Haupt Recruitment.';
    }
    if (is_tax('job_category')) {
        $term = get_queried_object();
        return 'Browse ' . $term->name . ' jobs. Find the latest vacancies in ' . $term->name . ' with Haupt Recruitment.';
    }
    
    // Category archive
    if (is_category()) {
        $term = get_queried_object();
        $description = $term->description;
        if ($description) {
            return wp_strip_all_tags($description, true);
        }
        return 'Browse articles in ' . $term->name . '. Latest news, insights and career advice from Haupt Recruitment.';
    }
    
    // Tag archive
    if (is_tag()) {
        $term = get_queried_object();
        return 'Browse articles tagged with "' . $term->name . '". Latest insights and career advice from Haupt Recruitment.';
    }
    
    // Author archive
    if (is_author()) {
        return 'Articles by ' . get_the_author() . '. Career advice and industry insights from Haupt Recruitment.';
    }
    
    // Search results
    if (is_search()) {
        return 'Search results for "' . get_search_query() . '". Find jobs, career guides and articles on Haupt Recruitment.';
    }
    
    // 404 page
    if (is_404()) {
        return 'The page you are looking for could not be found. Browse our jobs and career guides or contact us for assistance.';
    }
    
    // Blog archive
    if (is_home()) {
        return 'Latest news, insights and career advice from Haupt Recruitment. Expert guidance for maritime, offshore, renewables and construction careers.';
    }
    
    // Default fallback - never return empty
    return 'Specialist recruitment for the UK Power, Wind, Offshore, HV & Cable sectors. Find your next job or hire top talent with Haupt Recruitment.';
}
