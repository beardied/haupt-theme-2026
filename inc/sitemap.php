<?php
/**
 * XML Sitemap Generator
 * Auto-generates sitemap.xml for search engines
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Add sitemap rewrite rule - use priority 5 to run early
 */
add_action('init', function() {
    // Main sitemap index
    add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap=1', 'top');
    // Individual sitemaps
    add_rewrite_rule('^sitemap-([a-z0-9-]+)\.xml$', 'index.php?sitemap=$matches[1]', 'top');
}, 5);

/**
 * Add sitemap query var
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'sitemap';
    return $vars;
});

/**
 * Generate and serve sitemap - use parse_request for earlier interception
 */
add_action('parse_request', function($wp) {
    if (!isset($wp->query_vars['sitemap'])) {
        return;
    }
    
    $sitemap = $wp->query_vars['sitemap'];
    
    // Set headers
    header('Content-Type: application/xml; charset=UTF-8');
    header('X-Robots-Tag: noindex, follow', true);
    
    // Disable caching
    header('Cache-Control: no-cache, must-revalidate');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    
    // Main sitemap index
    if ($sitemap === '1' || $sitemap === 1) {
        haupt_generate_sitemap_index();
    } else {
        // Individual sitemap
        haupt_generate_sitemap_section($sitemap);
    }
    
    exit;
}, 5);

/**
 * Force rewrite rules flush on theme activation
 */
add_action('after_switch_theme', function() {
    flush_rewrite_rules();
});

/**
 * Alternative sitemap handler - checks REQUEST_URI directly
 * This catches requests even if rewrite rules fail
 */
add_action('init', function() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check if this is a sitemap request
    if (preg_match('#^/sitemap(-[a-z0-9-]+)?\.xml$#', $request_uri, $matches)) {
        $sitemap = isset($matches[1]) ? ltrim($matches[1], '-') : '1';
        
        // Set headers
        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex, follow', true);
        header('Cache-Control: no-cache, must-revalidate');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        
        // Main sitemap index
        if ($sitemap === '1' || $sitemap === 1) {
            haupt_generate_sitemap_index();
        } else {
            // Individual sitemap
            haupt_generate_sitemap_section($sitemap);
        }
        
        exit;
    }
}, 1);

/**
 * Add flush sitemap action link
 */
add_action('admin_init', function() {
    if (isset($_GET['haupt_flush_sitemap']) && current_user_can('manage_options')) {
        flush_rewrite_rules();
        wp_redirect(admin_url('options-permalink.php?haupt_sitemap_flushed=1'));
        exit;
    }
});

/**
 * Add admin notice after flush
 */
add_action('admin_notices', function() {
    if (isset($_GET['haupt_sitemap_flushed'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Sitemap rewrite rules flushed. <a href="' . home_url('/sitemap.xml') . '" target="_blank">View sitemap</a></p></div>';
    }
});

/**
 * Generate Sitemap Index
 */
function haupt_generate_sitemap_index() {
    $sections = [
        'pages' => 'Pages',
        'posts' => 'Posts',
        'jobs' => 'Jobs',
        'career-guides' => 'Career Guides',
        'job-sectors' => 'Job Sectors',
        'job-locations' => 'Job Locations',
        'job-types' => 'Job Types',
        'job-categories' => 'Job Categories',
        'categories' => 'Categories',
        'tags' => 'Tags',
    ];
    ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($sections as $slug => $name) : 
        $sitemap_url = home_url('/sitemap-' . $slug . '.xml');
        $lastmod = haupt_get_last_modified($slug);
    ?>
    <sitemap>
        <loc><?php echo esc_url($sitemap_url); ?></loc>
        <lastmod><?php echo esc_html($lastmod); ?></lastmod>
    </sitemap>
    <?php endforeach; ?>
</sitemapindex>
    <?php
}

/**
 * Generate Individual Sitemap Section
 */
function haupt_generate_sitemap_section($section) {
    ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 
                            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <?php
    switch ($section) {
        case 'pages':
            haupt_sitemap_pages();
            break;
        case 'posts':
            haupt_sitemap_posts();
            break;
        case 'jobs':
            haupt_sitemap_jobs();
            break;
        case 'career-guides':
            haupt_sitemap_career_guides();
            break;
        case 'job-sectors':
            haupt_sitemap_taxonomy('job_sector');
            break;
        case 'job-locations':
            haupt_sitemap_taxonomy('job_location');
            break;
        case 'job-types':
            haupt_sitemap_taxonomy('job_type');
            break;
        case 'job-categories':
            haupt_sitemap_taxonomy('job_category');
            break;
        case 'categories':
            haupt_sitemap_taxonomy('category');
            break;
        case 'tags':
            haupt_sitemap_taxonomy('post_tag');
            break;
        default:
            // Invalid section
            break;
    }
    ?>
</urlset>
    <?php
}

/**
 * Get last modified date for a section
 */
function haupt_get_last_modified($section) {
    global $wpdb;
    
    switch ($section) {
        case 'pages':
            $date = $wpdb->get_var("SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish'");
            break;
        case 'posts':
            $date = $wpdb->get_var("SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'");
            break;
        case 'jobs':
            $date = $wpdb->get_var("SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_type = 'job' AND post_status = 'publish'");
            break;
        case 'career-guides':
            $date = $wpdb->get_var("SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_type = 'role_expertise' AND post_status = 'publish'");
            break;
        default:
            $date = current_time('mysql', 1);
    }
    
    return $date ? mysql2date('Y-m-d\TH:i:s+00:00', $date) : current_time('c');
}

/**
 * Output sitemap URL entry
 */
function haupt_sitemap_url($loc, $lastmod, $priority = '0.5', $changefreq = 'weekly') {
    ?>
    <url>
        <loc><?php echo esc_url($loc); ?></loc>
        <lastmod><?php echo esc_html($lastmod); ?></lastmod>
        <changefreq><?php echo esc_html($changefreq); ?></changefreq>
        <priority><?php echo esc_html($priority); ?></priority>
    </url>
    <?php
}

/**
 * Get proper permalink for a post
 * Handles cases where get_permalink returns ugly URLs
 */
function haupt_get_sitemap_permalink($post) {
    $permalink = get_permalink($post);
    
    // If it's already a pretty URL, return it
    if (strpos($permalink, '?') === false) {
        return $permalink;
    }
    
    // Otherwise, construct the URL manually based on post type
    $post_type = $post->post_type;
    $slug = $post->post_name;
    
    switch ($post_type) {
        case 'page':
            return home_url('/' . $slug . '/');
        case 'post':
            // Use date-based URL structure
            $date = strtotime($post->post_date);
            return home_url('/' . date('Y', $date) . '/' . date('m', $date) . '/' . $slug . '/');
        case 'job':
            return home_url('/jobs/' . $slug . '/');
        case 'role_expertise':
            return home_url('/career-guides/' . $slug . '/');
        default:
            return $permalink;
    }
}

/**
 * Pages Sitemap
 */
function haupt_sitemap_pages() {
    $pages = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified',
        'order' => 'DESC',
    ]);
    
    foreach ($pages as $page) {
        $priority = ($page->ID == get_option('page_on_front')) ? '1.0' : '0.6';
        $changefreq = 'weekly';
        $lastmod = mysql2date('Y-m-d\TH:i:s+00:00', $page->post_modified_gmt);
        
        haupt_sitemap_url(haupt_get_sitemap_permalink($page), $lastmod, $priority, $changefreq);
    }
}

/**
 * Posts Sitemap
 */
function haupt_sitemap_posts() {
    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified',
        'order' => 'DESC',
    ]);
    
    foreach ($posts as $post) {
        $lastmod = mysql2date('Y-m-d\TH:i:s+00:00', $post->post_modified_gmt);
        haupt_sitemap_url(haupt_get_sitemap_permalink($post), $lastmod, '0.6', 'weekly');
    }
}

/**
 * Jobs Sitemap
 */
function haupt_sitemap_jobs() {
    $jobs = get_posts([
        'post_type' => 'job',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified',
        'order' => 'DESC',
    ]);
    
    foreach ($jobs as $job) {
        $lastmod = mysql2date('Y-m-d\TH:i:s+00:00', $job->post_modified_gmt);
        haupt_sitemap_url(haupt_get_sitemap_permalink($job), $lastmod, '0.8', 'daily');
    }
}

/**
 * Career Guides Sitemap
 */
function haupt_sitemap_career_guides() {
    $guides = get_posts([
        'post_type' => 'role_expertise',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified',
        'order' => 'DESC',
    ]);
    
    foreach ($guides as $guide) {
        $lastmod = mysql2date('Y-m-d\TH:i:s+00:00', $guide->post_modified_gmt);
        haupt_sitemap_url(haupt_get_sitemap_permalink($guide), $lastmod, '0.7', 'weekly');
    }
}

/**
 * Taxonomy Sitemap
 */
function haupt_sitemap_taxonomy($taxonomy) {
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ]);
    
    if (is_wp_error($terms) || empty($terms)) {
        return;
    }
    
    foreach ($terms as $term) {
        $term_link = get_term_link($term);
        if (is_wp_error($term_link)) {
            continue;
        }
        
        // Get most recent post in this term for lastmod
        $recent_post = get_posts([
            'post_type' => 'any',
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $term->term_id,
                ],
            ],
            'posts_per_page' => 1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ]);
        
        if (!empty($recent_post)) {
            $lastmod = mysql2date('Y-m-d\TH:i:s+00:00', $recent_post[0]->post_modified_gmt);
        } else {
            $lastmod = current_time('c');
        }
        
        haupt_sitemap_url($term_link, $lastmod, '0.5', 'weekly');
    }
}

/**
 * Add sitemap to robots.txt
 */
add_filter('robots_txt', function($output, $public) {
    if ($public) {
        $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";
    }
    return $output;
}, 10, 2);


