<?php
/**
 * Breadcrumb Functions
 * Generates breadcrumb navigation with Schema markup
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Generate breadcrumbs
 */
function haupt_get_breadcrumbs() {
    // Don't show on homepage
    if (is_front_page()) {
        return '';
    }
    
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'title' => __('Home', 'haupt-recruitment'),
        'url' => home_url('/'),
    ];
    
    // Build breadcrumb trail
    if (is_home()) {
        $breadcrumbs[] = [
            'title' => __('News', 'haupt-recruitment'),
            'url' => '',
        ];
    } elseif (is_single()) {
        $post_type = get_post_type();
        
        if ($post_type === 'post') {
            $breadcrumbs[] = [
                'title' => __('News', 'haupt-recruitment'),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
        } elseif ($post_type === 'job') {
            $breadcrumbs[] = [
                'title' => __('Jobs', 'haupt-recruitment'),
                'url' => get_post_type_archive_link('job'),
            ];
        } elseif ($post_type === 'job_role') {
            // Job Role Guides: Home > Job Role Guides > Category > Post
            $breadcrumbs[] = [
                'title' => __('Job Role Guides', 'haupt-recruitment'),
                'url' => get_post_type_archive_link('job_role'),
            ];
            
            // Get the category for this job role
            $terms = get_the_terms(get_the_ID(), 'job_role_category');
            if (!empty($terms) && !is_wp_error($terms)) {
                // Get the first category and its parents
                $term = $terms[0];
                $term_parents = [];
                
                // Build parent chain
                $parent = $term;
                while ($parent && $parent->parent) {
                    $parent = get_term($parent->parent, 'job_role_category');
                    if ($parent && !is_wp_error($parent)) {
                        $term_parents[] = $parent;
                    }
                }
                
                // Add parent categories (reversed to get root first)
                $term_parents = array_reverse($term_parents);
                foreach ($term_parents as $parent_term) {
                    $breadcrumbs[] = [
                        'title' => $parent_term->name,
                        'url' => get_term_link($parent_term),
                    ];
                }
                
                // Add the direct category
                $breadcrumbs[] = [
                    'title' => $term->name,
                    'url' => get_term_link($term),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_page()) {
        // Get parent pages
        $parents = get_post_ancestors(get_the_ID());
        $parents = array_reverse($parents);
        
        foreach ($parents as $parent_id) {
            $breadcrumbs[] = [
                'title' => get_the_title($parent_id),
                'url' => get_permalink($parent_id),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_archive()) {
        if (is_category()) {
            $breadcrumbs[] = [
                'title' => __('News', 'haupt-recruitment'),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            $breadcrumbs[] = [
                'title' => single_cat_title('', false),
                'url' => '',
            ];
        } elseif (is_tag()) {
            $breadcrumbs[] = [
                'title' => __('News', 'haupt-recruitment'),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            $breadcrumbs[] = [
                'title' => single_tag_title('', false),
                'url' => '',
            ];
        } elseif (is_post_type_archive()) {
            $post_type_obj = get_post_type_object(get_post_type());
            $breadcrumbs[] = [
                'title' => $post_type_obj->label,
                'url' => '',
            ];
        } elseif (is_tax()) {
            $term = get_queried_object();
            $taxonomy = get_taxonomy($term->taxonomy);
            
            if ($taxonomy->object_type[0] === 'job') {
                $breadcrumbs[] = [
                    'title' => __('Jobs', 'haupt-recruitment'),
                    'url' => get_post_type_archive_link('job'),
                ];
            } elseif ($term->taxonomy === 'job_role_category') {
                $breadcrumbs[] = [
                    'title' => __('Job Role Guides', 'haupt-recruitment'),
                    'url' => get_post_type_archive_link('job_role'),
                ];
                
                // Build parent category chain
                $term_parents = [];
                $parent = $term;
                while ($parent && $parent->parent) {
                    $parent = get_term($parent->parent, 'job_role_category');
                    if ($parent && !is_wp_error($parent)) {
                        $term_parents[] = $parent;
                    }
                }
                
                // Add parent categories
                $term_parents = array_reverse($term_parents);
                foreach ($term_parents as $parent_term) {
                    $breadcrumbs[] = [
                        'title' => $parent_term->name,
                        'url' => get_term_link($parent_term),
                    ];
                }
            }
            
            $breadcrumbs[] = [
                'title' => $term->name,
                'url' => '',
            ];
        } elseif (is_author()) {
            $breadcrumbs[] = [
                'title' => sprintf(__('Author: %s', 'haupt-recruitment'), get_the_author()),
                'url' => '',
            ];
        } elseif (is_date()) {
            if (is_year()) {
                $breadcrumbs[] = [
                    'title' => get_the_date('Y'),
                    'url' => '',
                ];
            } elseif (is_month()) {
                $breadcrumbs[] = [
                    'title' => get_the_date('F Y'),
                    'url' => '',
                ];
            } elseif (is_day()) {
                $breadcrumbs[] = [
                    'title' => get_the_date('F j, Y'),
                    'url' => '',
                ];
            }
        }
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search: %s', 'haupt-recruitment'), get_search_query()),
            'url' => '',
        ];
    } elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('Page Not Found', 'haupt-recruitment'),
            'url' => '',
        ];
    }
    
    // Build HTML
    $html = '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'haupt-recruitment') . '">';
    $html .= '<div class="container breadcrumbs-container">';
    $html .= '<ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    $position = 1;
    $total = count($breadcrumbs);
    
    foreach ($breadcrumbs as $index => $crumb) {
        $is_last = ($index === $total - 1);
        
        $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        if ($is_last || empty($crumb['url'])) {
            $html .= '<span itemprop="name">' . esc_html($crumb['title']) . '</span>';
        } else {
            $html .= '<a href="' . esc_url($crumb['url']) . '" itemprop="item">';
            $html .= '<span itemprop="name">' . esc_html($crumb['title']) . '</span>';
            $html .= '</a>';
        }
        
        $html .= '<meta itemprop="position" content="' . $position . '" />';
        $html .= '</li>';
        
        if (!$is_last) {
            $html .= '<li class="breadcrumbs-separator" aria-hidden="true">';
            $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
            $html .= '<polyline points="9 18 15 12 9 6"></polyline>';
            $html .= '</svg>';
            $html .= '</li>';
        }
        
        $position++;
    }
    
    $html .= '</ol>';
    $html .= '</div>';
    
    // JSON-LD Schema
    $html .= haupt_get_breadcrumb_schema($breadcrumbs);
    
    $html .= '</nav>';
    
    return $html;
}

// Breadcrumb schema is now handled in schema.php - haupt_get_breadcrumb_schema() function
