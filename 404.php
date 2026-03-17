<?php
/**
 * 404 Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();
?>

<!-- 404 Section -->
<section class="section section-error">
    <div class="container">
        <div class="error-content" data-aos="fade-up">
            <div class="error-code">404</div>
            <h1 class="error-title"><?php _e('Page Not Found', 'haupt-recruitment'); ?></h1>
            <p class="error-description"><?php _e('Sorry, the page you are looking for does not exist or has been moved.', 'haupt-recruitment'); ?></p>
            
            <div class="error-search">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="search-form-wrapper">
                        <input type="search" name="s" class="search-input" placeholder="<?php _e('Search our site...', 'haupt-recruitment'); ?>" required>
                        <button type="submit" class="search-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="error-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <?php _e('Back to Homepage', 'haupt-recruitment'); ?>
                </a>
                <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-outline">
                    <?php _e('Browse Jobs', 'haupt-recruitment'); ?>
                </a>
            </div>
            
            <!-- Quick Links -->
            <div class="error-quick-links">
                <h3><?php _e('Popular Pages', 'haupt-recruitment'); ?></h3>
                <div class="quick-links-grid">
                    <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="quick-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <?php _e('Job Search', 'haupt-recruitment'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('upload-cv'))); ?>" class="quick-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <?php _e('Upload CV', 'haupt-recruitment'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="quick-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        <?php _e('Contact Us', 'haupt-recruitment'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="quick-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <?php _e('About Us', 'haupt-recruitment'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
