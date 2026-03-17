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
                
                <!-- CTA Buttons -->
                <div class="header-actions">
                    <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-outline btn-sm">
                        <?php _e('Find Jobs', 'haupt-recruitment'); ?>
                    </a>
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

<!-- Main Content -->
<main id="main-content" class="site-main" style="padding-top: var(--header-height);">
