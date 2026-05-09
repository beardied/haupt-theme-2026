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
    
    // Header / Logo Section
    $wp_customize->add_section('haupt_header', [
        'title' => __('Header & Logo', 'haupt-recruitment'),
        'priority' => 20,
    ]);
    
    // Logo Image
    $wp_customize->add_setting('haupt_logo', [
        'default' => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'haupt_logo', [
        'label' => __('Logo Image', 'haupt-recruitment'),
        'description' => __('Upload a logo image to replace the text logo. Recommended: SVG or PNG with transparent background.', 'haupt-recruitment'),
        'section' => 'haupt_header',
        'mime_type' => 'image',
    ]));
    
    // Logo Max Width
    $wp_customize->add_setting('haupt_logo_width', [
        'default' => '200',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('haupt_logo_width', [
        'label' => __('Logo Max Width (px)', 'haupt-recruitment'),
        'section' => 'haupt_header',
        'type' => 'number',
        'input_attrs' => [
            'min' => 50,
            'max' => 600,
            'step' => 5,
        ],
    ]);
    
    // Logo Padding Top
    $wp_customize->add_setting('haupt_logo_padding_top', [
        'default' => '16',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('haupt_logo_padding_top', [
        'label' => __('Logo Padding Top (px)', 'haupt-recruitment'),
        'section' => 'haupt_header',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ],
    ]);
    
    // Logo Padding Bottom
    $wp_customize->add_setting('haupt_logo_padding_bottom', [
        'default' => '16',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('haupt_logo_padding_bottom', [
        'label' => __('Logo Padding Bottom (px)', 'haupt-recruitment'),
        'section' => 'haupt_header',
        'type' => 'number',
        'input_attrs' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ],
    ]);
    
    // Header Height
    $wp_customize->add_setting('haupt_header_height', [
        'default' => '80',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('haupt_header_height', [
        'label' => __('Header Height (px)', 'haupt-recruitment'),
        'description' => __('Total height of the header bar.', 'haupt-recruitment'),
        'section' => 'haupt_header',
        'type' => 'number',
        'input_attrs' => [
            'min' => 40,
            'max' => 200,
            'step' => 1,
        ],
    ]);
    
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
    $logo_width = get_theme_mod('haupt_logo_width', 200);
    $logo_pad_top = get_theme_mod('haupt_logo_padding_top', 16);
    $logo_pad_bottom = get_theme_mod('haupt_logo_padding_bottom', 16);
    $header_height = get_theme_mod('haupt_header_height', 80);
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr($primary); ?>;
            --color-accent: <?php echo esc_attr($accent); ?>;
            --header-height: <?php echo esc_attr($header_height); ?>px;
        }
        .site-header {
            height: <?php echo esc_attr($header_height); ?>px;
        }
        .header-container {
            height: 100%;
        }
        .site-logo--image {
            display: flex;
            align-items: center;
            height: 100%;
            padding-top: <?php echo esc_attr($logo_pad_top); ?>px;
            padding-bottom: <?php echo esc_attr($logo_pad_bottom); ?>px;
        }
        .site-logo--image img {
            max-width: <?php echo esc_attr($logo_width); ?>px;
            width: 100%;
            height: auto;
            max-height: calc(<?php echo esc_attr($header_height); ?>px - <?php echo esc_attr($logo_pad_top + $logo_pad_bottom); ?>px);
            object-fit: contain;
        }
    </style>
    <?php
}, 100);
