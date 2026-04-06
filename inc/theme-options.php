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
    $admin_value = get_option('haupt_stat_' . $stat, false);
    
    // If admin setting exists and is not empty, use it
    if ($admin_value !== false && $admin_value !== '' && $admin_value !== '0') {
        return (int) $admin_value;
    }
    
    // Second: Check customizer directly
    $customizer_value = get_theme_mod('haupt_stat_' . $stat, false);
    if ($customizer_value !== false && $customizer_value !== '' && $customizer_value !== '0') {
        return (int) $customizer_value;
    }
    
    // Return hardcoded default
    return $default_value;
}

/**
 * Get hero content
 */
function haupt_get_hero($field) {
    return haupt_get_option('hero_' . $field);
}

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
