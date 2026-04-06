<?php
/**
 * Theme Options
 * Hardcoded theme options - NO PLUGIN DEPENDENCIES
 *
 * @package Haupt_Recruitment_2026
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme default options
 */
function haupt_get_default_options() {
    return [
        // Contact Information
        'phone_number' => '0800 123 4567',
        'email_address' => 'info@hauptrecruitment.co.uk',
        'address' => 'Haupt Recruitment Ltd\n123 Business Park\nManchester\nM1 1AA',
        
        // Social Media
        'linkedin_url' => '',
        'twitter_url' => '',
        'facebook_url' => '',
        
        // Homepage Stats
        'stat_placements' => 2500,
        'stat_clients' => 150,
        'stat_candidates' => 15000,
        'stat_years' => 15,
        
        // Homepage Content
        'hero_title' => 'Powering Careers in the Energy Sector',
        'hero_description' => 'Connecting exceptional talent with leading companies across UK Power, Wind, Offshore, HV & Cable sectors. Your next opportunity starts here.',
    ];
}

/**
 * Get theme option
 */
function haupt_get_option($key, $default = '') {
    $defaults = haupt_get_default_options();
    $default_value = isset($defaults[$key]) ? $defaults[$key] : $default;
    
    // Try customizer first
    $customizer_value = get_theme_mod('haupt_' . $key);
    if ($customizer_value !== '' && $customizer_value !== false) {
        return $customizer_value;
    }
    
    // Fall back to options
    $options = get_option('haupt_theme_options', []);
    if (isset($options[$key]) && $options[$key] !== '') {
        return $options[$key];
    }
    
    return $default_value;
}

/**
 * Get phone number
 */
function haupt_get_phone() {
    return haupt_get_option('phone_number');
}

/**
 * Get email
 */
function haupt_get_email() {
    return haupt_get_option('email_address');
}

/**
 * Get address
 */
function haupt_get_address() {
    return haupt_get_option('address');
}

/**
 * Get social URL
 */
function haupt_get_social($network) {
    return haupt_get_option($network . '_url');
}

/**
 * Get stat value
 */
function haupt_get_stat($stat) {
    // Default values for stats
    $defaults = [
        'placements' => 2500,
        'clients' => 150,
        'candidates' => 15000,
        'years' => 15,
        'retention' => 98,
    ];
    
    $default_value = isset($defaults[$stat]) ? $defaults[$stat] : 0;
    
    // First check admin settings (Haupt Settings page)
    $admin_value = get_option('haupt_stat_' . $stat);
    
    // If admin setting exists and is not empty, use it
    if ($admin_value !== false && $admin_value !== '') {
        return (int) $admin_value;
    }
    
    // Fallback to customizer/theme options
    $customizer_value = haupt_get_option('stat_' . $stat, $default_value);
    
    return (int) $customizer_value;
}

/**
 * Get hero content
 */
function haupt_get_hero($field) {
    return haupt_get_option('hero_' . $field);
}

/**
 * Register theme options in customizer
 */
add_action('customize_register', function($wp_customize) {
    
    // Add Haupt Theme Options section
    $wp_customize->add_section('haupt_theme_options', [
        'title' => __('Haupt Theme Options', 'haupt-recruitment'),
        'priority' => 30,
    ]);
    
    // Phone Number
    $wp_customize->add_setting('haupt_phone_number', [
        'default' => '0800 123 4567',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_phone_number', [
        'label' => __('Phone Number', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'text',
    ]);
    
    // Email Address
    $wp_customize->add_setting('haupt_email_address', [
        'default' => 'info@hauptrecruitment.co.uk',
        'sanitize_callback' => 'sanitize_email',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_email_address', [
        'label' => __('Email Address', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'email',
    ]);
    
    // Address
    $wp_customize->add_setting('haupt_address', [
        'default' => "Haupt Recruitment Ltd\n123 Business Park\nManchester\nM1 1AA",
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_address', [
        'label' => __('Address', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'textarea',
    ]);
    
    // LinkedIn
    $wp_customize->add_setting('haupt_linkedin_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_linkedin_url', [
        'label' => __('LinkedIn URL', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'url',
    ]);
    
    // Twitter
    $wp_customize->add_setting('haupt_twitter_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_twitter_url', [
        'label' => __('Twitter URL', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'url',
    ]);
    
    // Facebook
    $wp_customize->add_setting('haupt_facebook_url', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_facebook_url', [
        'label' => __('Facebook URL', 'haupt-recruitment'),
        'section' => 'haupt_theme_options',
        'type' => 'url',
    ]);
    
    // Homepage Stats Section
    $wp_customize->add_section('haupt_homepage_stats', [
        'title' => __('Homepage Stats', 'haupt-recruitment'),
        'priority' => 35,
    ]);
    
    // Stat: Placements
    $wp_customize->add_setting('haupt_stat_placements', [
        'default' => 2500,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_stat_placements', [
        'label' => __('Placements Made', 'haupt-recruitment'),
        'section' => 'haupt_homepage_stats',
        'type' => 'number',
    ]);
    
    // Stat: Clients
    $wp_customize->add_setting('haupt_stat_clients', [
        'default' => 150,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_stat_clients', [
        'label' => __('Client Companies', 'haupt-recruitment'),
        'section' => 'haupt_homepage_stats',
        'type' => 'number',
    ]);
    
    // Stat: Candidates
    $wp_customize->add_setting('haupt_stat_candidates', [
        'default' => 15000,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_stat_candidates', [
        'label' => __('Qualified Candidates', 'haupt-recruitment'),
        'section' => 'haupt_homepage_stats',
        'type' => 'number',
    ]);
    
    // Stat: Years
    $wp_customize->add_setting('haupt_stat_years', [
        'default' => 15,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_stat_years', [
        'label' => __('Years Experience', 'haupt-recruitment'),
        'section' => 'haupt_homepage_stats',
        'type' => 'number',
    ]);
    
    // Homepage Content Section
    $wp_customize->add_section('haupt_homepage_content', [
        'title' => __('Homepage Content', 'haupt-recruitment'),
        'priority' => 36,
    ]);
    
    // Hero Title
    $wp_customize->add_setting('haupt_hero_title', [
        'default' => 'Powering Careers in the Energy Sector',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_hero_title', [
        'label' => __('Hero Title', 'haupt-recruitment'),
        'section' => 'haupt_homepage_content',
        'type' => 'text',
    ]);
    
    // Hero Description
    $wp_customize->add_setting('haupt_hero_description', [
        'default' => 'Connecting exceptional talent with leading companies across UK Power, Wind, Offshore, HV & Cable sectors. Your next opportunity starts here.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('haupt_hero_description', [
        'label' => __('Hero Description', 'haupt-recruitment'),
        'section' => 'haupt_homepage_content',
        'type' => 'textarea',
    ]);
});

/**
 * Helper function for template files - checks if page meta exists
 * Uses post meta (WordPress native) - NO ACF
 */
function haupt_get_meta($key, $post_id = null, $default = '') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $value = get_post_meta($post_id, $key, true);
    
    return $value !== '' ? $value : $default;
}

/**
 * Check if page should have transparent header
 */
function haupt_has_transparent_header() {
    if (is_front_page()) {
        return true;
    }
    
    return haupt_get_meta('transparent_header') === '1';
}

/**
 * Check if page header should be hidden
 */
function haupt_hide_page_header() {
    return haupt_get_meta('hide_page_header') === '1';
}
