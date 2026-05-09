<?php
/**
 * Theme Header
 *
 * @package Haupt_Recruitment_2026
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // SEO Meta Title
    $meta_title = haupt_get_seo_title();
    ?>
    <title><?php echo esc_html($meta_title); ?></title>
    
    <?php
    // SEO Meta Description
    $meta_description = haupt_get_seo_description();
    if ($meta_description) :
    ?>
    <meta name="description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:title" content="<?php echo esc_attr($meta_title); ?>">
    <?php if ($meta_description) : ?>
    <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>
    <?php if (has_post_thumbnail()) : ?>
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>">
    <?php endif; ?>
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="twitter:title" content="<?php echo esc_attr($meta_title); ?>">
    <?php if ($meta_description) : ?>
    <meta property="twitter:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>
    <?php if (has_post_thumbnail()) : ?>
    <meta property="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(null, 'large')); ?>">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
    
    <?php wp_head(); ?>
    
    <!-- Schema.org Organization Markup -->
    <?php echo haupt_get_organization_schema(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip to content link -->
<a href="#main-content" class="sr-only">
    <?php _e('Skip to main content', 'haupt-recruitment'); ?>
</a>

<!-- Site Header -->
<header class="site-header" id="site-header">
    <div class="container header-container">
        <!-- Logo -->
        <?php
        $logo_id = get_theme_mod('haupt_logo');
        if ($logo_id) :
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
            $logo_alt = get_bloginfo('name');
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo site-logo--image">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" width="<?php echo esc_attr(get_theme_mod('haupt_logo_width', 200)); ?>">
        </a>
        <?php else : ?>
        <?php if ($logo_id) : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo site-logo--image">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" width="<?php echo esc_attr(get_theme_mod('haupt_logo_width', 200)); ?>">
        </a>
        <?php else : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <span class="logo-main">HAUPT</span>
            <span class="logo-sub">Recruitment</span>
        </a>
        <?php endif; ?>
        <?php endif; ?>
        
        <!-- Mobile Menu Toggle -->
        <button 
            type="button" 
            class="mobile-menu-toggle" 
            aria-label="<?php _e('Toggle menu', 'haupt-recruitment'); ?>"
            aria-expanded="false"
            aria-controls="primary-navigation"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        
        <!-- Primary Navigation -->
        <nav class="primary-navigation" id="primary-navigation" role="navigation">
            <div class="desktop-menu">
                <?php
                if (haupt_nav_menu_has_items('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class' => 'nav-menu',
                        'container' => false,
                        'depth' => 2,
                        'walker' => new Haupt_Walker_Nav_Menu(),
                    ]);
                } else {
                    // Fallback until menu is configured
                    ?><ul class="nav-menu">
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>"><?php _e('Career Guides', 'haupt-recruitment'); ?></a></li>
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>"><?php _e('Jobs', 'haupt-recruitment'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/register-with-us/')); ?>"><?php _e('Register', 'haupt-recruitment'); ?></a></li>
                        <li class="last-item"><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact Us', 'haupt-recruitment'); ?></a></li>
                    </ul><?php
                }
                ?>
            </div>
        </nav>
    </div>
    
    <!-- Progress Bar -->
    <div class="scroll-progress" id="scroll-progress"></div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu-header">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <span class="logo-main">HAUPT</span>
            <span class="logo-sub">Recruitment</span>
        </a>
        <button 
            type="button" 
            class="mobile-menu-close" 
            aria-label="<?php _e('Close menu', 'haupt-recruitment'); ?>"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    
    <nav class="mobile-menu-nav" role="navigation">
        <?php
        if (haupt_nav_menu_has_items('mobile')) {
            wp_nav_menu([
                'theme_location' => 'mobile',
                'menu_class' => 'mobile-menu-list',
                'container' => false,
                'depth' => 2,
            ]);
        } elseif (haupt_nav_menu_has_items('primary')) {
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class' => 'mobile-menu-list',
                'container' => false,
                'depth' => 2,
            ]);
        } else {
            ?><ul class="mobile-menu-list">
                <li><a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>"><?php _e('Career Guides', 'haupt-recruitment'); ?></a></li>
                <li><a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>"><?php _e('Jobs', 'haupt-recruitment'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/register-with-us/')); ?>"><?php _e('Register', 'haupt-recruitment'); ?></a></li>
            </ul>
            <ul class="mobile-menu-links">
                <li class="last-item"><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact Us', 'haupt-recruitment'); ?></a></li>
            </ul><?php
        }
        ?>
    </nav>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

<!-- Main Content -->
<main id="main-content" class="site-main" style="padding-top: var(--header-height);">
