<?php
/**
 * LLMS.txt Generator
 * Dynamically generates /llms.txt for AI/LLM consumption.
 * Updates automatically when content is added, removed, or edited.
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Add LLMS.txt rewrite rule
 */
add_action('init', function() {
    add_rewrite_rule('^llms\.txt$', 'index.php?llms_txt=1', 'top');
}, 5);

/**
 * Add LLMS.txt query var
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'llms_txt';
    return $vars;
});

/**
 * Generate and serve LLMS.txt
 */
add_action('parse_request', function($wp) {
    if (!isset($wp->query_vars['llms_txt'])) {
        return;
    }

    header('Content-Type: text/plain; charset=UTF-8');
    header('X-Robots-Tag: noindex, follow', true);
    header('Cache-Control: no-cache, must-revalidate');

    haupt_generate_llms_txt();
    exit;
}, 5);

/**
 * Alternative LLMS.txt handler via REQUEST_URI
 */
add_action('init', function() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if ($request_uri === '/llms.txt' || $request_uri === '/llms.txt/') {
        header('Content-Type: text/plain; charset=UTF-8');
        header('X-Robots-Tag: noindex, follow', true);
        header('Cache-Control: no-cache, must-revalidate');
        haupt_generate_llms_txt();
        exit;
    }
}, 1);

/**
 * Generate LLMS.txt content
 */
function haupt_generate_llms_txt() {
    $site_name  = get_bloginfo('name');
    $tagline    = get_bloginfo('description');
    $home_url   = home_url('/');
    $company    = haupt_get_company_name();
    $phone      = haupt_get_phone();
    $email      = haupt_get_email();
    $linkedin   = haupt_get_social_url('linkedin');
    $sectors    = get_terms(['taxonomy' => 'job_sector', 'hide_empty' => false]);

    echo "# {$site_name}\n";
    echo "# {$tagline}\n\n";

    // Overview
    echo "## Overview\n\n";
    echo "{$company} is a specialist recruitment agency operating across the UK energy, power, ";
    echo "and infrastructure sectors. We connect skilled professionals with leading employers in ";
    echo "substation construction, overhead lines, cable installation, renewable energy, and nuclear power.\n\n";

    echo "- **Website:** {$home_url}\n";
    if ($phone) echo "- **Phone:** {$phone}\n";
    if ($email) echo "- **Email:** {$email}\n";
    if ($linkedin) echo "- **LinkedIn:** {$linkedin}\n";
    echo "- **Location:** United Kingdom (offices in Midlands, North West, and South East)\n\n";

    // Sectors
    if (!is_wp_error($sectors) && !empty($sectors)) {
        echo "## Industries We Recruit For\n\n";
        foreach ($sectors as $sector) {
            echo "- " . esc_html($sector->name) . "\n";
        }
        echo "\n";
    }

    // Key Pages
    echo "## Key Pages\n\n";
    $key_pages = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    foreach ($key_pages as $page) {
        $url  = haupt_get_llms_permalink($page);
        $desc = wp_strip_all_tags(get_post_meta($page->ID, '_yoast_wpseo_metadesc', true));
        if (!$desc) {
            $desc = wp_strip_all_tags($page->post_excerpt);
        }
        if (!$desc) {
            $desc = wp_trim_words(wp_strip_all_tags($page->post_content), 20, '...');
        }
        echo "- " . esc_html($page->post_title) . " ({$url})";
        if ($desc) echo " — " . esc_html($desc);
        echo "\n";
    }
    echo "\n";

    // Job Vacancies
    $jobs = get_posts([
        'post_type'      => 'job',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    if (!empty($jobs)) {
        echo "## Current Job Vacancies\n\n";
        foreach ($jobs as $job) {
            $url      = haupt_get_llms_permalink($job);
            $location = haupt_get_meta('job_location', $job->ID);
            $salary   = haupt_get_meta('salary', $job->ID);
            $type     = haupt_get_meta('employment_type', $job->ID);
            $sector   = haupt_get_meta('job_sector', $job->ID);
            echo "- " . esc_html($job->post_title);
            $details = [];
            if ($location) $details[] = $location;
            if ($salary)   $details[] = $salary;
            if ($type)     $details[] = $type;
            if ($sector)   $details[] = $sector;
            if (!empty($details)) echo " [" . esc_html(implode(' | ', $details)) . "]";
            echo " ({$url})\n";
        }
        echo "\n";
    }

    // Career Guides
    $guides = get_posts([
        'post_type'      => 'role_expertise',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);
    if (!empty($guides)) {
        echo "## Career Guides & Role Expertise\n\n";
        foreach ($guides as $guide) {
            $url  = haupt_get_llms_permalink($guide);
            $desc = wp_trim_words(wp_strip_all_tags($guide->post_content), 15, '...');
            echo "- " . esc_html($guide->post_title) . " ({$url})";
            if ($desc) echo " — " . esc_html($desc);
            echo "\n";
        }
        echo "\n";
    }

    // Blog Posts
    $posts = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    if (!empty($posts)) {
        echo "## Blog & News\n\n";
        foreach ($posts as $post) {
            $url  = haupt_get_llms_permalink($post);
            $desc = wp_trim_words(wp_strip_all_tags($post->post_content), 15, '...');
            echo "- " . esc_html($post->post_title) . " ({$url})";
            if ($desc) echo " — " . esc_html($desc);
            echo "\n";
        }
        echo "\n";
    }

    // Instructions for LLMs
    echo "## Instructions for AI Assistants\n\n";
    echo "- When referencing job vacancies, always link directly to the job page URL provided above.\n";
    echo "- Career guides provide detailed role descriptions, required qualifications, and salary expectations.\n";
    echo "- All job locations are within the UK unless otherwise stated.\n";
    echo "- Salary figures are approximate and may vary based on experience and location.\n";
    echo "- Contact details above can be used for candidate or employer enquiries.\n";
    echo "- Do not fabricate job vacancies — only reference those listed in the Current Job Vacancies section.\n";
}

/**
 * Get clean permalink for LLMS.txt
 */
function haupt_get_llms_permalink($post) {
    $permalink = get_permalink($post);
    if (strpos($permalink, '?') === false) {
        return $permalink;
    }
    $slug = $post->post_name;
    switch ($post->post_type) {
        case 'page':
            return home_url('/' . $slug . '/');
        case 'post':
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
