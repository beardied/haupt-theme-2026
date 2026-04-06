<?php
/**
 * Theme Customizer
 *
 * @package Haupt_Recruitment_2026
 */

add_action('customize_register', function($wp_customize) {
    
    // Colors Section
    $wp_customize->add_section('haupt_colors', [
        'title' => __('Theme Colors', 'haupt-recruitment'),
        'priority' => 35,
    ]);
    
    // Primary Color
    $wp_customize->add_setting('haupt_primary_color', [
        'default' => '#0a1628',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'haupt_primary_color', [
        'label' => __('Primary Color', 'haupt-recruitment'),
        'section' => 'haupt_colors',
    ]));
    
    // Accent Color
    $wp_customize->add_setting('haupt_accent_color', [
        'default' => '#f59e0b',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'haupt_accent_color', [
        'label' => __('Accent Color', 'haupt-recruitment'),
        'section' => 'haupt_colors',
    ]));
    
    // Footer Section
    $wp_customize->add_section('haupt_footer', [
        'title' => __('Footer Settings', 'haupt-recruitment'),
        'priority' => 40,
    ]);
    
    // Footer Text
    $wp_customize->add_setting('haupt_footer_text', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('haupt_footer_text', [
        'label' => __('Footer Copyright Text', 'haupt-recruitment'),
        'section' => 'haupt_footer',
        'type' => 'textarea',
    ]);
    
    // Homepage Hero Section
    $wp_customize->add_section('haupt_hero', [
        'title' => __('Homepage Hero', 'haupt-recruitment'),
        'priority' => 25,
    ]);
    
    // Hero Background Image
    $wp_customize->add_setting('haupt_hero_bg_image', [
        'default' => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'haupt_hero_bg_image', [
        'label' => __('Hero Background Image', 'haupt-recruitment'),
        'description' => __('Upload a background image for the homepage hero section. Recommended size: 1920x800px or wider.', 'haupt-recruitment'),
        'section' => 'haupt_hero',
        'mime_type' => 'image',
    ]));
    
    // Hero Overlay Opacity
    $wp_customize->add_setting('haupt_hero_overlay_opacity', [
        'default' => '60',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('haupt_hero_overlay_opacity', [
        'label' => __('Overlay Darkness', 'haupt-recruitment'),
        'description' => __('Higher values make the image darker (better for text readability).', 'haupt-recruitment'),
        'section' => 'haupt_hero',
        'type' => 'range',
        'input_attrs' => [
            'min' => 0,
            'max' => 100,
            'step' => 5,
        ],
    ]);
    
    // Hero Overlay Color
    $wp_customize->add_setting('haupt_hero_overlay_color', [
        'default' => '#0a1628',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'haupt_hero_overlay_color', [
        'label' => __('Overlay Color', 'haupt-recruitment'),
        'description' => __('Choose the overlay color. Dark blue is recommended for readability.', 'haupt-recruitment'),
        'section' => 'haupt_hero',
    ]));
});

/**
 * Output customizer CSS
 */
add_action('wp_head', function() {
    $primary = get_theme_mod('haupt_primary_color', '#0a1628');
    $accent = get_theme_mod('haupt_accent_color', '#f59e0b');
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr($primary); ?>;
            --color-accent: <?php echo esc_attr($accent); ?>;
        }
    </style>
    <?php
}, 100);
