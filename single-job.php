<?php
/**
 * Single Job Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get job meta
    $location = haupt_get_meta('job_location');
    $salary = haupt_get_meta('salary');
    $job_type = haupt_get_meta('job_type');
    $sector = haupt_get_meta('job_sector');
    ?>
    
    <!-- Job Header -->
    <header class="article-header">
        <div class="article-header-bg">
            <img src="<?php echo HAUPT_URI; ?>/assets/images/default-hero.jpg" alt="" aria-hidden="true">
        </div>
        <div class="article-header-overlay"></div>
        <div class="article-header-content">
            <div class="article-meta-top">
                <?php if ($sector) : ?>
                    <span class="article-category"><?php echo esc_html($sector); ?></span>
                <?php endif; ?>
                <?php if ($job_type) : ?>
                    <span class="article-date"><?php echo esc_html($job_type); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="article-title"><?php the_title(); ?></h1>
            <div class="article-author">
                <?php if ($location) : ?>
                    <span class="author-name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <?php echo esc_html($location); ?>
                    </span>
                <?php endif; ?>
                <?php if ($salary) : ?>
                    <span class="author-role"><?php echo esc_html($salary); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- Breadcrumbs -->
    <?php echo haupt_get_breadcrumbs(); ?>
    
    <!-- Job Content -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('article-single'); ?>>
        <div class="section">
            <div class="container">
                <div class="article-layout">
                    <!-- Main Content -->
                    <div class="article-main">
                        <div class="content entry-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Apply Button -->
                        <div class="job-apply-section" style="margin-top: var(--space-8); padding: var(--space-6); background: var(--gray-50); border-radius: var(--radius-xl); text-align: center;">
                            <h3 style="margin-bottom: var(--space-4);"><?php _e('Interested in this role?', 'haupt-recruitment'); ?></h3>
                            <p style="margin-bottom: var(--space-4); color: var(--text-secondary);"><?php _e('Get in touch to enquire about this position or register your CV for similar opportunities.', 'haupt-recruitment'); ?></p>
                            <div style="display: flex; gap: var(--space-4); justify-content: center; flex-wrap: wrap;">
                                <a href="<?php echo esc_url(home_url('/contact/?job_id=' . get_the_ID())); ?>" class="btn btn-primary btn-lg">
                                    <?php _e('Enquire about this Role', 'haupt-recruitment'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-outline btn-lg">
                                    <?php _e('View More Jobs', 'haupt-recruitment'); ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Share -->
                        <div class="article-share">
                            <span class="share-label"><?php _e('Share:', 'haupt-recruitment'); ?></span>
                            <div class="share-buttons">
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn linkedin" aria-label="Share on LinkedIn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" aria-label="Share on Twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn facebook" aria-label="Share on Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <button class="share-btn copy" aria-label="Copy link" data-url="<?php the_permalink(); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Jobs Sidebar -->
                    <aside class="expert-sidebar">
                        <!-- Search Widget -->
                        <div class="sidebar-widget">
                            <h4><?php _e('Search Jobs', 'haupt-recruitment'); ?></h4>
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url(get_post_type_archive_link('job')); ?>" style="display: flex; gap: var(--space-2);">
                                <input type="hidden" name="post_type" value="job">
                                <input type="search" class="form-input" placeholder="<?php esc_attr_e('Search jobs...', 'haupt-recruitment'); ?>" value="" name="s" style="flex: 1;">
                                <button type="submit" class="btn btn-primary" style="padding: var(--space-3);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Recent Jobs Widget -->
                        <div class="sidebar-widget">
                            <h4><?php _e('Recent Jobs', 'haupt-recruitment'); ?></h4>
                            <ul class="sidebar-links">
                                <?php
                                $recent_jobs = get_posts([
                                    'post_type' => 'job',
                                    'posts_per_page' => 5,
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                    'post__not_in' => [get_the_ID()],
                                ]);
                                
                                if (!empty($recent_jobs)) :
                                    foreach ($recent_jobs as $job) :
                                ?>
                                    <li>
                                        <a href="<?php echo get_permalink($job->ID); ?>"><?php echo esc_html($job->post_title); ?></a>
                                    </li>
                                <?php 
                                    endforeach;
                                else :
                                ?>
                                    <li><?php _e('No other jobs available.', 'haupt-recruitment'); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <!-- CTA Widget -->
                        <div class="sidebar-widget sidebar-widget-highlight">
                            <h4><?php _e('Looking for a Role?', 'haupt-recruitment'); ?></h4>
                            <p><?php _e('Browse our current job openings or register your CV with us.', 'haupt-recruitment'); ?></p>
                            <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-primary btn-block">
                                <?php _e('View Jobs', 'haupt-recruitment'); ?>
                            </a>
                        </div>
                        
                        <!-- Dynamic Sidebar Widgets (if any) -->
                        <?php if (is_active_sidebar('jobs-sidebar')) : ?>
                            <?php dynamic_sidebar('jobs-sidebar'); ?>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </div>
    </article>
    
    <!-- Related Jobs -->
    <?php
    $related_jobs = get_posts([
        'post_type' => 'job',
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
        'orderby' => 'date',
    ]);
    
    if (!empty($related_jobs)) :
    ?>
    <section class="section section-gray">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Similar Jobs', 'haupt-recruitment'); ?></h2>
            </div>
            <div class="grid grid-3">
                <?php foreach ($related_jobs as $job) : 
                    $job_location = haupt_get_meta('job_location', $job->ID);
                    $job_salary = haupt_get_meta('salary', $job->ID);
                    $job_type_val = haupt_get_meta('job_type', $job->ID);
                ?>
                    <article class="card job-card" data-aos="fade-up">
                        <div class="card-content">
                            <div class="card-meta">
                                <?php if ($job_location) : ?>
                                    <span class="card-meta-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <?php echo esc_html($job_location); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($job_type_val) : ?>
                                    <span class="card-meta-item"><?php echo esc_html($job_type_val); ?></span>
                                <?php endif; ?>
                            </div>
                            <h3 class="card-title">
                                <a href="<?php echo get_permalink($job->ID); ?>"><?php echo esc_html($job->post_title); ?></a>
                            </h3>
                            <?php if ($job_salary) : ?>
                                <span class="card-salary"><?php echo esc_html($job_salary); ?></span>
                            <?php endif; ?>
                            <a href="<?php echo get_permalink($job->ID); ?>" class="btn btn-sm btn-primary" style="margin-top: var(--space-4);">
                                <?php _e('View Job', 'haupt-recruitment'); ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
<?php
endwhile;

get_footer();
