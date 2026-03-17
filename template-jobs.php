<?php
/**
 * Template Name: Job Listings
 * Description: Full job listings page with filters
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get filter options
$sectors = get_terms(['taxonomy' => 'job_sector', 'hide_empty' => true]);
$locations = get_terms(['taxonomy' => 'job_location', 'hide_empty' => true]);
$job_types = get_terms(['taxonomy' => 'job_type', 'hide_empty' => true]);

// Get current page of jobs
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$jobs_query = new WP_Query([
    'post_type' => 'job',
    'posts_per_page' => 12,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
]);
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/jobs-header.jpg" alt="" aria-hidden="true">
    </div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Career Opportunities', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('Current Vacancies', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Discover your next role in the UK power and energy sector. Browse our current opportunities or register your CV for future openings.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Job Search & Filter -->
<section class="section section-gray job-filters-section">
    <div class="container">
        <form class="job-filters" id="job-filter-form" data-aos="fade-up">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="filter-keyword" class="filter-label"><?php _e('Keywords', 'haupt-recruitment'); ?></label>
                    <div class="filter-input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="filter-keyword" name="keyword" class="filter-input" placeholder="<?php _e('Job title, keywords...', 'haupt-recruitment'); ?>">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label for="filter-sector" class="filter-label"><?php _e('Sector', 'haupt-recruitment'); ?></label>
                    <select id="filter-sector" name="sector" class="filter-select">
                        <option value=""><?php _e('All Sectors', 'haupt-recruitment'); ?></option>
                        <?php foreach ($sectors as $sector) : ?>
                            <option value="<?php echo esc_attr($sector->slug); ?>"><?php echo esc_html($sector->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filter-location" class="filter-label"><?php _e('Location', 'haupt-recruitment'); ?></label>
                    <select id="filter-location" name="location" class="filter-select">
                        <option value=""><?php _e('All Locations', 'haupt-recruitment'); ?></option>
                        <?php foreach ($locations as $location) : ?>
                            <option value="<?php echo esc_attr($location->slug); ?>"><?php echo esc_html($location->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filter-type" class="filter-label"><?php _e('Job Type', 'haupt-recruitment'); ?></label>
                    <select id="filter-type" name="type" class="filter-select">
                        <option value=""><?php _e('All Types', 'haupt-recruitment'); ?></option>
                        <?php foreach ($job_types as $type) : ?>
                            <option value="<?php echo esc_attr($type->slug); ?>"><?php echo esc_html($type->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group filter-submit">
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <?php _e('Search Jobs', 'haupt-recruitment'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Job Results -->
<section class="section" id="job-results-section">
    <div class="container">
        <div class="job-results-header" data-aos="fade-up">
            <h2 class="job-results-count">
                <?php 
                $total_jobs = wp_count_posts('job')->publish;
                printf(_n('%s Job Available', '%s Jobs Available', $total_jobs, 'haupt-recruitment'), number_format($total_jobs)); 
                ?>
            </h2>
            <div class="job-results-sort">
                <label for="sort-jobs"><?php _e('Sort by:', 'haupt-recruitment'); ?></label>
                <select id="sort-jobs" class="filter-select">
                    <option value="newest"><?php _e('Newest First', 'haupt-recruitment'); ?></option>
                    <option value="salary-high"><?php _e('Salary: High to Low', 'haupt-recruitment'); ?></option>
                    <option value="salary-low"><?php _e('Salary: Low to High', 'haupt-recruitment'); ?></option>
                </select>
            </div>
        </div>
        
        <!-- Loading Indicator -->
        <div id="job-loading" class="job-loading" style="display: none;">
            <div class="loading-spinner"></div>
            <p><?php _e('Loading jobs...', 'haupt-recruitment'); ?></p>
        </div>
        
        <!-- Job Grid -->
        <div id="job-results" class="job-results">
            <?php if ($jobs_query->have_posts()) : ?>
                <div class="grid grid-auto">
                    <?php 
                    $delay = 0;
                    while ($jobs_query->have_posts()) : $jobs_query->the_post();
                        $location = haupt_get_meta('job_location');
                        $salary = haupt_get_meta('salary');
                        $job_type = haupt_get_meta('job_type');
                        $job_sectors = get_the_terms(get_the_ID(), 'job_sector');
                    ?>
                        <article class="card job-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('card'); ?>
                                    </a>
                                    <?php if ($job_sectors && !is_wp_error($job_sectors)) : ?>
                                        <span class="card-badge"><?php echo esc_html($job_sectors[0]->name); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-content">
                                <div class="card-meta">
                                    <?php if ($location) : ?>
                                        <span class="card-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <?php echo esc_html($location); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($job_type) : ?>
                                        <span class="card-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                            </svg>
                                            <?php echo esc_html($job_type); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="card-text">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </div>
                                
                                <div class="card-footer">
                                    <?php if ($salary) : ?>
                                        <span class="card-salary"><?php echo esc_html($salary); ?></span>
                                    <?php endif; ?>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">
                                        <?php _e('View Details', 'haupt-recruitment'); ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php 
                        $delay += 100;
                        if ($delay > 500) $delay = 0;
                    endwhile; 
                    ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    echo paginate_links([
                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format' => '?paged=%#%',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $jobs_query->max_num_pages,
                        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                        'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                    ]);
                    ?>
                </div>
                
            <?php else : ?>
                <div class="no-results" data-aos="fade-up">
                    <div class="no-results-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3><?php _e('No jobs found', 'haupt-recruitment'); ?></h3>
                    <p><?php _e('We currently don\'t have any positions matching your criteria. Please try different filters or register your CV for future opportunities.', 'haupt-recruitment'); ?></p>
                    <div class="no-results-buttons">
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('upload-cv'))); ?>" class="btn btn-primary">
                            <?php _e('Register Your CV', 'haupt-recruitment'); ?>
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-outline">
                            <?php _e('Contact Us', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

<!-- Job Alert CTA -->
<section class="section section-dark">
    <div class="container">
        <div class="job-alert-cta" data-aos="fade-up">
            <div class="job-alert-content">
                <h2><?php _e('Never Miss an Opportunity', 'haupt-recruitment'); ?></h2>
                <p><?php _e('Sign up for job alerts and be the first to know about new positions in your field.', 'haupt-recruitment'); ?></p>
            </div>
            <form class="job-alert-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="job_alert_signup">
                <?php wp_nonce_field('job_alert_nonce', 'job_alert_nonce'); ?>
                <input type="email" name="email" class="form-input" placeholder="<?php _e('Enter your email', 'haupt-recruitment'); ?>" required>
                <button type="submit" class="btn btn-primary">
                    <?php _e('Subscribe', 'haupt-recruitment'); ?>
                </button>
            </form>
        </div>
    </div>
</section>

<?php
get_footer();
