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
define('HAUPT_VERSION', '1.0.52');
define('HAUPT_DIR', get_template_directory());
define('HAUPT_URI', get_template_directory_uri());

/**
 * Get file version for cache busting
 * Uses file modification time in dev mode, theme version in production
 * 
 * @param string $file_path Path to file relative to theme directory
 * @return string Version string
 */
function haupt_get_file_version($file_path = '') {
    $full_path = HAUPT_DIR . '/' . ltrim($file_path, '/');
    if (file_exists($full_path)) {
        return HAUPT_VERSION . '.' . filemtime($full_path);
    }
    return HAUPT_VERSION;
}

/**
 * Theme Setup
 */
add_action('after_setup_theme', function() {
    // Add theme support
    // Note: title-tag is handled manually in header.php for SEO control
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
    add_theme_support('dark-editor-style');
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
        'company' => __('Company Menu', 'haupt-recruitment'),
        'footer-bottom' => __('Footer Bottom Menu', 'haupt-recruitment'),
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
    // Local Fonts
    wp_enqueue_style(
        'haupt-fonts',
        HAUPT_URI . '/assets/css/fonts.css',
        [],
        haupt_get_file_version('assets/css/fonts.css')
    );
    
    // AOS Animation Library (local)
    wp_enqueue_style(
        'aos-css',
        HAUPT_URI . '/assets/css/aos.css',
        [],
        haupt_get_file_version('assets/css/aos.css')
    );
    
    wp_enqueue_script(
        'aos-js',
        HAUPT_URI . '/assets/js/aos.js',
        [],
        haupt_get_file_version('assets/js/aos.js'),
        true
    );
    
    // Main stylesheet - with file-based cache busting
    wp_enqueue_style(
        'haupt-style',
        HAUPT_URI . '/assets/css/main.css',
        [],
        haupt_get_file_version('assets/css/main.css')
    );
    
    // Page templates stylesheet - with file-based cache busting
    wp_enqueue_style(
        'haupt-templates',
        HAUPT_URI . '/assets/css/page-templates.css',
        ['haupt-style'],
        haupt_get_file_version('assets/css/page-templates.css')
    );
    
    // Gutenberg styles (frontend) - with file-based cache busting
    wp_enqueue_style(
        'haupt-gutenberg',
        HAUPT_URI . '/assets/css/gutenberg.css',
        ['haupt-style'],
        haupt_get_file_version('assets/css/gutenberg.css')
    );
    
    // Main JavaScript - with file-based cache busting
    wp_enqueue_script(
        'haupt-main',
        HAUPT_URI . '/assets/js/main.js',
        ['aos-js'],
        haupt_get_file_version('assets/js/main.js'),
        true
    );
    
    // Pass PHP variables to JavaScript
    wp_localize_script('haupt-main', 'hauptData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url('haupt/v1/'),
        'nonce' => wp_create_nonce('haupt_nonce'),
        'homeUrl' => home_url(),
    ]);
    
    // Remove feed enqueues
    wp_dequeue_style('wp-block-library-theme');
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Form styles and scripts for registration/contact pages
    if (is_page_template('template-registration.php') || 
        is_page_template('template-employer-contact.php') || 
        is_page_template('template-48hour-optout.php')) {
        
        wp_enqueue_style(
            'haupt-forms',
            HAUPT_URI . '/assets/css/form-styles.css',
            ['haupt-style'],
            haupt_get_file_version('assets/css/form-styles.css')
        );
        
        wp_enqueue_script(
            'signature-pad',
            'https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js',
            [],
            '4.1.7',
            true
        );
        
        wp_enqueue_script(
            'haupt-forms',
            HAUPT_URI . '/assets/js/forms.js',
            ['signature-pad'],
            haupt_get_file_version('assets/js/forms.js'),
            true
        );
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
        haupt_get_file_version('assets/css/admin.css')
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
 * Disable Comments Completely
 */
// Disable support for comments and trackbacks in post types
add_action('admin_init', function() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', function($comments) {
    return [];
}, 10, 2);

// Remove comments page from admin menu
add_action('admin_menu', function() {
    remove_menu_page('edit-comments.php');
});

// Remove comments from admin bar
add_action('wp_before_admin_bar_render', function() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});

// Disable comments metabox on dashboard
add_action('wp_dashboard_setup', function() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
});

// Remove comment links from frontend
add_action('wp', function() {
    wp_deregister_script('comment-reply');
});

/**
 * Disable RSS Feeds
 */
// Redirect all feed URLs to homepage
add_action('template_redirect', function() {
    if (is_feed()) {
        wp_redirect(home_url(), 301);
        exit;
    }
});

// Remove feed links from wp_head
add_action('init', function() {
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
});

// Disable feed URL rewriting
add_filter('rewrite_rules_array', function($rules) {
    foreach ($rules as $rule => $rewrite) {
        if (preg_match('/feed|/(feed|rdf|rss|rss2|atom)//i', $rule)) {
            unset($rules[$rule]);
        }
    }
    return $rules;
});

/**
 * Include required files
 */
require_once HAUPT_DIR . '/inc/theme-options.php';
require_once HAUPT_DIR . '/inc/admin-settings.php';
require_once HAUPT_DIR . '/inc/taxonomy-images.php';
require_once HAUPT_DIR . '/inc/schema.php';
require_once HAUPT_DIR . '/inc/schema-meta-box.php';
require_once HAUPT_DIR . '/inc/breadcrumbs.php';
require_once HAUPT_DIR . '/inc/template-functions.php';
require_once HAUPT_DIR . '/inc/sitemap.php';
require_once HAUPT_DIR . '/inc/customizer.php';
require_once HAUPT_DIR . '/inc/class-haupt-forms.php';

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
    
    register_sidebar([
        'name' => __('Jobs Sidebar', 'haupt-recruitment'),
        'id' => 'jobs-sidebar',
        'description' => __('Widgets for the jobs sidebar', 'haupt-recruitment'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
});

/**
 * Register custom query vars
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'job_id';
    return $vars;
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
    // Custom Post Type: Role Expertise
    // URL: /role-expertise/post-title/ (no category in URL)
    // ==========================================
    register_post_type('role_expertise', [
        'labels' => [
            'name' => __('Role Expertise', 'haupt-recruitment'),
            'singular_name' => __('Role Expertise', 'haupt-recruitment'),
            'add_new' => __('Add New Role', 'haupt-recruitment'),
            'add_new_item' => __('Add New Role Expertise', 'haupt-recruitment'),
            'edit_item' => __('Edit Role Expertise', 'haupt-recruitment'),
            'new_item' => __('New Role Expertise', 'haupt-recruitment'),
            'view_item' => __('View Role Expertise', 'haupt-recruitment'),
            'search_items' => __('Search Role Expertise', 'haupt-recruitment'),
            'not_found' => __('No role expertise found', 'haupt-recruitment'),
            'not_found_in_trash' => __('No role expertise found in trash', 'haupt-recruitment'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'role-expertise',
            'with_front' => false,
        ],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'menu_icon' => 'dashicons-welcome-learn-more',
        'show_in_rest' => true,
        'hierarchical' => false,
        'taxonomies' => ['role_expertise_category'],
    ]);
    
    // ==========================================
    // Taxonomy: Role Expertise Categories (10 Sectors)
    // Used for breadcrumbs and filtering (not in URL)
    // ==========================================
    register_taxonomy('role_expertise_category', 'role_expertise', [
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
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => false, // No public URL for categories
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
    check_ajax_referer('haupt_jobs_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $sector = isset($_POST['sector']) ? sanitize_text_field($_POST['sector']) : '';
    $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = [
        'post_type' => 'job',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'paged' => $page,
    ];
    
    // Keyword search
    if ($keyword) {
        $args['s'] = $keyword;
    }
    
    // Taxonomy filters (support multiple comma-separated values)
    $tax_query = [];
    
    if ($sector) {
        $sectors = array_filter(explode(',', $sector));
        if (!empty($sectors)) {
            $tax_query[] = [
                'taxonomy' => 'job_sector',
                'field' => 'slug',
                'terms' => $sectors,
                'operator' => 'IN',
            ];
        }
    }
    
    if ($location) {
        $locations = array_filter(explode(',', $location));
        if (!empty($locations)) {
            $tax_query[] = [
                'taxonomy' => 'job_location',
                'field' => 'slug',
                'terms' => $locations,
                'operator' => 'IN',
            ];
        }
    }
    
    if ($category) {
        $categories = array_filter(explode(',', $category));
        if (!empty($categories)) {
            $tax_query[] = [
                'taxonomy' => 'job_category',
                'field' => 'slug',
                'terms' => $categories,
                'operator' => 'IN',
            ];
        }
    }
    
    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }
    
    $query = new WP_Query($args);
    $html = '';
    $delay = 0;
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $job_location = haupt_get_meta('job_location');
            $salary = haupt_get_meta('salary');
            $job_type = haupt_get_meta('job_type');
            $job_sectors = get_the_terms(get_the_ID(), 'job_sector');
            
            ob_start();
            ?>
            <article class="card job-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('card'); ?>
                        </a>
                        <?php if ($job_sectors && !is_wp_error($job_sectors)) : ?>
                            <span class="card-badge"><?php echo esc_html($job_sectors[0]->name); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="card-content">
                    <div class="card-meta">
                        <?php if ($job_location) : ?>
                            <span class="card-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <?php echo esc_html($job_location); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($job_type) : ?>
                            <span class="card-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                                <?php echo esc_html($job_type); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <div class="card-text">
                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                    </div>
                    
                    <div class="card-footer">
                        <?php if ($salary) : ?>
                            <span class="card-salary"><?php echo esc_html($salary); ?></span>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">
                            <?php _e('View Details', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                </div>
            </article>
            <?php
            $html .= ob_get_clean();
            $delay += 100;
            if ($delay > 500) $delay = 0;
        }
    }
    
    wp_reset_postdata();
    
    wp_send_json_success([
        'html' => $html,
        'found_posts' => $query->found_posts,
        'has_more' => $query->max_num_pages > $page,
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

/**
 * Job Archive Filter - Handle taxonomy filtering via URL parameters
 */
add_action('pre_get_posts', function($query) {
    // Only modify job archive main query
    if (is_admin() || !$query->is_main_query() || !is_post_type_archive('job')) {
        return;
    }
    
    $tax_query = [];
    
    // Filter by sector
    if (!empty($_GET['sector'])) {
        $tax_query[] = [
            'taxonomy' => 'job_sector',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['sector']),
        ];
    }
    
    // Filter by location
    if (!empty($_GET['location'])) {
        $tax_query[] = [
            'taxonomy' => 'job_location',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['location']),
        ];
    }
    
    // Filter by type
    if (!empty($_GET['type'])) {
        $tax_query[] = [
            'taxonomy' => 'job_type',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['type']),
        ];
    }
    
    // Filter by category
    if (!empty($_GET['category'])) {
        $tax_query[] = [
            'taxonomy' => 'job_category',
            'field' => 'slug',
            'terms' => sanitize_text_field($_GET['category']),
        ];
    }
    
    // Apply tax query if we have filters
    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $query->set('tax_query', $tax_query);
    }
    
    // Handle search
    if (!empty($_GET['s'])) {
        $query->set('s', sanitize_text_field($_GET['s']));
    }
});
