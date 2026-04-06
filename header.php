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
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <span class="logo-main">HAUPT</span>
            <span class="logo-sub">Recruitment</span>
        </a>
        
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
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu',
                    'container' => false,
                    'fallback_cb' => false,
                    'depth' => 2,
                ]);
                ?>
                
                <!-- Navigation Links -->
                <ul class="header-nav-links">
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>"><?php _e('Career Guides', 'haupt-recruitment'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>"><?php _e('Jobs', 'haupt-recruitment'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/register-with-us/')); ?>"><?php _e('Register', 'haupt-recruitment'); ?></a></li>
                </ul>
                
                <!-- CTA Buttons -->
                <div class="header-actions">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-sm">
                        <?php _e('Contact Us', 'haupt-recruitment'); ?>
                    </a>
                </div>
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
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_class' => 'mobile-menu-list',
            'container' => false,
            'fallback_cb' => false,
            'depth' => 2,
        ]);
        ?>
        
        <ul class="mobile-menu-links">
            <li><a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>"><?php _e('Career Guides', 'haupt-recruitment'); ?></a></li>
            <li><a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>"><?php _e('Jobs', 'haupt-recruitment'); ?></a></li>
            <li><a href="<?php echo esc_url(home_url('/register-with-us/')); ?>"><?php _e('Register', 'haupt-recruitment'); ?></a></li>
        </ul>
    </nav>
    
    <div class="mobile-menu-footer">
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-lg">
            <?php _e('Contact Us', 'haupt-recruitment'); ?>
        </a>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

<!-- Main Content -->
<main id="main-content" class="site-main" style="padding-top: var(--header-height);">
