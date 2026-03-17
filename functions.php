<?php
/**
 * Haupt Recruitment 2026 Theme Functions
 *
 * @package Haupt_Recruitment_2026
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version for cache busting
define('HAUPT_VERSION', '1.0.7');
define('HAUPT_DIR', get_template_directory());
define('HAUPT_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
add_action('after_setup_theme', function() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_theme_support('automatic-feed-links');
    
    // Gutenberg Block Support
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');
    add_theme_support('align-wide');
    add_theme_support('align-full');
    
    // Block Color Palette
    add_theme_support('editor-color-palette', [
        [
            'name' => __('Primary Dark', 'haupt-recruitment'),
            'slug' => 'primary-dark',
            'color' => '#0a1628',
        ],
        [
            'name' => __('Accent', 'haupt-recruitment'),
            'slug' => 'accent',
            'color' => '#f59e0b',
        ],
        [
            'name' => __('Secondary', 'haupt-recruitment'),
            'slug' => 'secondary',
            'color' => '#0369a1',
        ],
        [
            'name' => __('Gray 50', 'haupt-recruitment'),
            'slug' => 'gray-50',
            'color' => '#f8fafc',
        ],
        [
            'name' => __('Gray 100', 'haupt-recruitment'),
            'slug' => 'gray-100',
            'color' => '#f1f5f9',
        ],
        [
            'name' => __('Gray 700', 'haupt-recruitment'),
            'slug' => 'gray-700',
            'color' => '#334155',
        ],
        [
            'name' => __('White', 'haupt-recruitment'),
            'slug' => 'white',
            'color' => '#ffffff',
        ],
    ]);
    
    // Block Font Sizes
    add_theme_support('editor-font-sizes', [
        [
            'name' => __('Small', 'haupt-recruitment'),
            'size' => 14,
            'slug' => 'small',
        ],
        [
            'name' => __('Normal', 'haupt-recruitment'),
            'size' => 18,
            'slug' => 'normal',
        ],
        [
            'name' => __('Large', 'haupt-recruitment'),
            'size' => 24,
            'slug' => 'large',
        ],
        [
            'name' => __('Extra Large', 'haupt-recruitment'),
            'size' => 32,
            'slug' => 'extra-large',
        ],
    ]);
    
    // Disable custom font sizes (use our scale)
    add_theme_support('disable-custom-font-sizes');
    
    // Add support for WooCommerce if needed
    // add_theme_support('woocommerce');
    
    // Register navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'haupt-recruitment'),
        'footer' => __('Footer Menu', 'haupt-recruitment'),
        'mobile' => __('Mobile Menu', 'haupt-recruitment'),
        'employers' => __('Employers Menu', 'haupt-recruitment'),
        'candidates' => __('Candidates Menu', 'haupt-recruitment'),
    ]);
    
    // Image sizes
    add_image_size('hero', 1920, 1080, true);
    add_image_size('card', 600, 400, true);
    add_image_size('thumbnail-wide', 400, 225, true);
    add_image_size('avatar', 200, 200, true);
    
    // Load text domain
    load_theme_textdomain('haupt-recruitment', HAUPT_DIR . '/languages');
});

/**
 * Enqueue Scripts and Styles
 */
add_action('wp_enqueue_scripts', function() {
    // Google Fonts
    wp_enqueue_style(
        'haupt-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap',
        [],
        null
    );
    
    // AOS Animation Library
    wp_enqueue_style(
        'aos-css',
        'https://unpkg.com/aos@2.3.1/dist/aos.css',
        [],
        '2.3.1'
    );
    
    wp_enqueue_script(
        'aos-js',
        'https://unpkg.com/aos@2.3.1/dist/aos.js',
        [],
        '2.3.1',
        true
    );
    
    // Main stylesheet
    wp_enqueue_style(
        'haupt-style',
        HAUPT_URI . '/assets/css/main.css',
        [],
        HAUPT_VERSION
    );
    
    // Page templates stylesheet
    wp_enqueue_style(
        'haupt-templates',
        HAUPT_URI . '/assets/css/page-templates.css',
        ['haupt-style'],
        HAUPT_VERSION
    );
    
    // Gutenberg styles (frontend)
    wp_enqueue_style(
        'haupt-gutenberg',
        HAUPT_URI . '/assets/css/gutenberg.css',
        ['haupt-style'],
        HAUPT_VERSION
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'haupt-main',
        HAUPT_URI . '/assets/js/main.js',
        ['aos-js'],
        HAUPT_VERSION,
        true
    );
    
    // Pass PHP variables to JavaScript
    wp_localize_script('haupt-main', 'hauptData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url('haupt/v1/'),
        'nonce' => wp_create_nonce('haupt_nonce'),
        'homeUrl' => home_url(),
    ]);
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
});

/**
 * Admin Styles
 */
add_action('admin_enqueue_scripts', function($hook) {
    wp_enqueue_style(
        'haupt-admin',
        HAUPT_URI . '/assets/css/admin.css',
        [],
        HAUPT_VERSION
    );
});

/**
 * Gutenberg Editor Styles
 */
add_action('enqueue_block_editor_assets', function() {
    wp_enqueue_style(
        'haupt-editor',
        HAUPT_URI . '/assets/css/gutenberg.css',
        [],
        HAUPT_VERSION
    );
});

/**
 * Include required files
 */
require_once HAUPT_DIR . '/inc/theme-options.php';
require_once HAUPT_DIR . '/inc/schema.php';
require_once HAUPT_DIR . '/inc/breadcrumbs.php';
require_once HAUPT_DIR . '/inc/template-functions.php';
require_once HAUPT_DIR . '/inc/customizer.php';

/**
 * Register Sidebars and Widget Areas
 */
add_action('widgets_init', function() {
    register_sidebar([
        'name' => __('Blog Sidebar', 'haupt-recruitment'),
        'id' => 'blog-sidebar',
        'description' => __('Widgets for the blog sidebar', 'haupt-recruitment'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
    
    register_sidebar([
        'name' => __('Footer Column 1', 'haupt-recruitment'),
        'id' => 'footer-1',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ]);
    
    register_sidebar([
        'name' => __('Footer Column 2', 'haupt-recruitment'),
        'id' => 'footer-2',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ]);
    
    register_sidebar([
        'name' => __('Footer Column 3', 'haupt-recruitment'),
        'id' => 'footer-3',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ]);
    
    register_sidebar([
        'name' => __('Footer Column 4', 'haupt-recruitment'),
        'id' => 'footer-4',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ]);
});

/**
 * Custom Excerpt Length
 */
add_filter('excerpt_length', function($length) {
    return 25;
}, 999);

add_filter('excerpt_more', function($more) {
    return '...';
});

/**
 * Add custom body classes
 */
add_filter('body_class', function($classes) {
    // Add page slug class
    if (is_singular()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add template class
    $template = get_page_template_slug();
    if ($template) {
        $classes[] = 'template-' . sanitize_html_class(str_replace('.php', '', $template));
    }
    
    // Add class if using transparent header
    if (haupt_has_transparent_header()) {
        $classes[] = 'has-transparent-header';
    }
    
    return $classes;
});

/**
 * Disable WordPress emoji scripts
 */
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

/**
 * Remove WordPress version from head
 */
add_filter('the_generator', '__return_empty_string');

/**
 * Add ACF Options Page if ACF is active
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Theme Settings', 'haupt-recruitment'),
        'menu_title' => __('Theme Settings', 'haupt-recruitment'),
        'menu_slug' => 'theme-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
    ]);
    
    acf_add_options_sub_page([
        'page_title' => __('Company Info', 'haupt-recruitment'),
        'menu_title' => __('Company Info', 'haupt-recruitment'),
        'parent_slug' => 'theme-settings',
    ]);
    
    acf_add_options_sub_page([
        'page_title' => __('Social Media', 'haupt-recruitment'),
        'menu_title' => __('Social Media', 'haupt-recruitment'),
        'parent_slug' => 'theme-settings',
    ]);
}

/**
 * Custom Post Type: Job Listings
 */
add_action('init', function() {
    
    // ==========================================
    // Custom Post Type: Job Role Guides
    // URL: /job-role/category-name/post-title/
    // ==========================================
    register_post_type('job_role', [
        'labels' => [
            'name' => __('Job Role Guides', 'haupt-recruitment'),
            'singular_name' => __('Job Role Guide', 'haupt-recruitment'),
            'add_new' => __('Add New Job Role', 'haupt-recruitment'),
            'add_new_item' => __('Add New Job Role Guide', 'haupt-recruitment'),
            'edit_item' => __('Edit Job Role Guide', 'haupt-recruitment'),
            'new_item' => __('New Job Role Guide', 'haupt-recruitment'),
            'view_item' => __('View Job Role Guide', 'haupt-recruitment'),
            'search_items' => __('Search Job Role Guides', 'haupt-recruitment'),
            'not_found' => __('No job role guides found', 'haupt-recruitment'),
            'not_found_in_trash' => __('No job role guides found in trash', 'haupt-recruitment'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'job-role/%job_role_category%',
            'with_front' => false,
        ],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'menu_icon' => 'dashicons-welcome-learn-more',
        'show_in_rest' => true,
        'hierarchical' => false, // Use categories for hierarchy, not pages
        'taxonomies' => ['job_role_category'], // Declare supported taxonomies
    ]);
    
    // ==========================================
    // Taxonomy: Job Role Categories (10 Sectors)
    // URL: /job-role-category/substations/
    // ==========================================
    register_taxonomy('job_role_category', 'job_role', [
        'labels' => [
            'name' => __('Categories', 'haupt-recruitment'),
            'singular_name' => __('Category', 'haupt-recruitment'),
            'search_items' => __('Search Categories', 'haupt-recruitment'),
            'all_items' => __('All Categories', 'haupt-recruitment'),
            'parent_item' => __('Parent Category', 'haupt-recruitment'),
            'parent_item_colon' => __('Parent Category:', 'haupt-recruitment'),
            'edit_item' => __('Edit Category', 'haupt-recruitment'),
            'update_item' => __('Update Category', 'haupt-recruitment'),
            'add_new_item' => __('Add New Category', 'haupt-recruitment'),
            'new_item_name' => __('New Category Name', 'haupt-recruitment'),
            'menu_name' => __('Categories', 'haupt-recruitment'),
        ],
        'hierarchical' => true, // Like categories (not tags)
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'job-role-category',
            'with_front' => false,
        ],
    ]);
    
    // ==========================================
    // Custom Post Type: Job Listings
    // URL: /jobs/senior-electrical-engineer/
    // ==========================================
    register_post_type('job', [
        'labels' => [
            'name' => __('Jobs', 'haupt-recruitment'),
            'singular_name' => __('Job', 'haupt-recruitment'),
            'add_new' => __('Add New Job', 'haupt-recruitment'),
            'add_new_item' => __('Add New Job', 'haupt-recruitment'),
            'edit_item' => __('Edit Job', 'haupt-recruitment'),
            'new_item' => __('New Job', 'haupt-recruitment'),
            'view_item' => __('View Job', 'haupt-recruitment'),
            'search_items' => __('Search Jobs', 'haupt-recruitment'),
            'not_found' => __('No jobs found', 'haupt-recruitment'),
            'not_found_in_trash' => __('No jobs found in trash', 'haupt-recruitment'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'jobs'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
    ]);
    
    // Job Categories
    register_taxonomy('job_category', 'job', [
        'labels' => [
            'name' => __('Job Categories', 'haupt-recruitment'),
            'singular_name' => __('Job Category', 'haupt-recruitment'),
        ],
        'hierarchical' => true,
        'rewrite' => ['slug' => 'job-category'],
        'show_in_rest' => true,
    ]);
    
    // Job Locations
    register_taxonomy('job_location', 'job', [
        'labels' => [
            'name' => __('Job Locations', 'haupt-recruitment'),
            'singular_name' => __('Job Location', 'haupt-recruitment'),
        ],
        'hierarchical' => false,
        'rewrite' => ['slug' => 'job-location'],
        'show_in_rest' => true,
    ]);
    
    // Job Sectors
    register_taxonomy('job_sector', 'job', [
        'labels' => [
            'name' => __('Job Sectors', 'haupt-recruitment'),
            'singular_name' => __('Job Sector', 'haupt-recruitment'),
        ],
        'hierarchical' => true,
        'rewrite' => ['slug' => 'sector'],
        'show_in_rest' => true,
    ]);
});

/**
 * Replace %job_role_category% placeholder in job_role URLs
 * Makes URLs like: /job-role/substations/electrical-engineer/
 */
add_filter('post_type_link', function($post_link, $post) {
    if ($post->post_type !== 'job_role') {
        return $post_link;
    }
    
    // Get the primary category
    $terms = get_the_terms($post->ID, 'job_role_category');
    
    if (!empty($terms) && !is_wp_error($terms)) {
        // Use first category
        $category_slug = $terms[0]->slug;
    } else {
        // Fallback if no category assigned
        $category_slug = 'uncategorized';
    }
    
    return str_replace('%job_role_category%', $category_slug, $post_link);
}, 10, 2);

/**
 * Add rewrite rules for job_role with category in URL
 * Pattern: /job-role/{category}/{postname}/
 */
add_action('init', function() {
    // Rule for single job_role posts: /job-role/category/post-name/
    add_rewrite_rule(
        '^job-role/([^/]+)/([^/]+)/?$',
        'index.php?job_role=$matches[2]',
        'top'
    );
    
    // Rule for category archives: /job-role-category/category-name/
    add_rewrite_rule(
        '^job-role-category/([^/]+)/?$',
        'index.php?job_role_category=$matches[1]',
        'top'
    );
    
    // Pagination for category archives
    add_rewrite_rule(
        '^job-role-category/([^/]+)/page/([0-9]+)/?$',
        'index.php?job_role_category=$matches[1]&paged=$matches[2]',
        'top'
    );
}, 20);

/**
 * Flush rewrite rules on theme activation
 */
add_action('after_switch_theme', function() {
    flush_rewrite_rules();
});

/**
 * AJAX handler for job search/filter
 */
add_action('wp_ajax_haupt_filter_jobs', 'haupt_filter_jobs');
add_action('wp_ajax_nopriv_haupt_filter_jobs', 'haupt_filter_jobs');

function haupt_filter_jobs() {
    check_ajax_referer('haupt_nonce', 'nonce');
    
    $sector = isset($_POST['sector']) ? sanitize_text_field($_POST['sector']) : '';
    $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
    
    $args = [
        'post_type' => 'job',
        'posts_per_page' => 12,
        'post_status' => 'publish',
    ];
    
    $tax_query = [];
    
    if ($sector) {
        $tax_query[] = [
            'taxonomy' => 'job_sector',
            'field' => 'slug',
            'terms' => $sector,
        ];
    }
    
    if ($location) {
        $tax_query[] = [
            'taxonomy' => 'job_location',
            'field' => 'slug',
            'terms' => $location,
        ];
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    $query = new WP_Query($args);
    $jobs = [];
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $jobs[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card'),
                'location' => haupt_get_meta('job_location', $post->ID),
                'salary' => haupt_get_meta('salary', $post->ID),
                'type' => haupt_get_meta('job_type', $post->ID),
            ];
        }
    }
    
    wp_reset_postdata();
    
    wp_send_json_success([
        'jobs' => $jobs,
        'found_posts' => $query->found_posts,
    ]);
}

/**
 * REST API Endpoints
 */
add_action('rest_api_init', function() {
    register_rest_route('haupt/v1', '/stats/', [
        'methods' => 'GET',
        'callback' => 'haupt_get_stats',
        'permission_callback' => '__return_true',
    ]);
});

function haupt_get_stats() {
    // These would ideally come from ACF options or calculated dynamically
    $stats = [
        'placements' => haupt_get_stat('placements'),
        'clients' => haupt_get_stat('clients'),
        'candidates' => haupt_get_stat('candidates'),
        'years' => haupt_get_stat('years'),
    ];
    
    return rest_ensure_response($stats);
}
