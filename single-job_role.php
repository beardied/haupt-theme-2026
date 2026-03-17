<?php
/**
 * Single Job Role Template
 * Uses the same layout as template-job-role-expert.php
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get meta values with fallbacks
    $reading_time = haupt_get_meta('reading_time', null, '5 min read');
    $salary_range = haupt_get_meta('salary_range');
    $experience_level = haupt_get_meta('experience_level');
    $job_locations = haupt_get_meta('job_locations');
    $last_updated = get_the_modified_date('j F Y');
    
    // Get category for this job role
    $terms = get_the_terms(get_the_ID(), 'job_role_category');
    $category = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
    
    // Get related job roles (same category)
    $related_roles = [];
    if ($category) {
        $related_roles = get_posts([
            'post_type' => 'job_role',
            'post__not_in' => [get_the_ID()],
            'numberposts' => 3,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => [
                [
                    'taxonomy' => 'job_role_category',
                    'field' => 'slug',
                    'terms' => $category->slug,
                ],
            ],
        ]);
    }
?>

<!-- Expert Guide Header -->
<header class="expert-header">
    <div class="expert-header-bg">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('hero'); ?>
        <?php else : ?>
            <div class="expert-header-pattern"></div>
        <?php endif; ?>
    </div>
    <div class="expert-header-overlay"></div>
    
    <div class="expert-header-content">
        <div class="container">
            <?php if ($category) : ?>
                <nav class="expert-parent-nav" data-aos="fade-down">
                    <a href="<?php echo get_term_link($category); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <?php echo esc_html($category->name); ?>
                    </a>
                </nav>
            <?php endif; ?>
            
            <span class="expert-label" data-aos="fade-up"><?php _e('Career Guide', 'haupt-recruitment'); ?></span>
            <h1 class="expert-title" data-aos="fade-up" data-aos-delay="100"><?php the_title(); ?></h1>
            
            <div class="expert-meta" data-aos="fade-up" data-aos-delay="200">
                <span class="expert-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo esc_html($reading_time); ?>
                </span>
                <?php if ($salary_range) : ?>
                <span class="expert-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <?php echo esc_html($salary_range); ?>
                </span>
                <?php endif; ?>
                <?php if ($experience_level) : ?>
                <span class="expert-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <?php echo esc_html($experience_level); ?>
                </span>
                <?php endif; ?>
                <span class="expert-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php printf(__('Updated %s', 'haupt-recruitment'), $last_updated); ?>
                </span>
            </div>
        </div>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Main Content -->
<article class="expert-article">
    <div class="section">
        <div class="container">
            <div class="expert-layout">
                
                <!-- Main Content -->
                <div class="expert-main">
                    
                    <?php if (has_blocks()) : ?>
                        <!-- Gutenberg Content -->
                        <div class="expert-content entry-content" data-aos="fade-up">
                            <?php the_content(); ?>
                        </div>
                    <?php else : ?>
                        <!-- Classic Editor Content -->
                        <div class="expert-content content" data-aos="fade-up">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- CTA Box -->
                    <div class="expert-cta-box" data-aos="fade-up">
                        <h3><?php printf(__('Ready to work as a %s?', 'haupt-recruitment'), get_the_title()); ?></h3>
                        <p><?php _e('Browse current opportunities or register your CV to be matched with suitable roles.', 'haupt-recruitment'); ?></p>
                        <div class="expert-cta-buttons">
                            <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-primary">
                                <?php _e('View Current Jobs', 'haupt-recruitment'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/upload-cv/')); ?>" class="btn btn-outline">
                                <?php _e('Register Your CV', 'haupt-recruitment'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- FAQ Schema -->
                    <?php echo haupt_get_faq_schema(); ?>
                    
                </div>
                
                <!-- Sidebar -->
                <aside class="expert-sidebar">
                    
                    <!-- Speak to Consultant -->
                    <div class="sidebar-widget" data-aos="fade-left">
                        <h4><?php _e('Speak to a Specialist', 'haupt-recruitment'); ?></h4>
                        <p><?php _e('Our consultants specialise in this field and can help with your career.', 'haupt-recruitment'); ?></p>
                        <?php $phone = haupt_get_option('phone_number'); if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="sidebar-phone">
                            <?php echo esc_html($phone); ?>
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-block">
                            <?php _e('Get in Touch', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                    
                    <!-- Related Job Roles -->
                    <?php if (!empty($related_roles)) : ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                        <h4><?php _e('Related Job Roles', 'haupt-recruitment'); ?></h4>
                        <ul class="sidebar-links">
                            <?php foreach ($related_roles as $role) : ?>
                                <li>
                                    <a href="<?php echo get_permalink($role->ID); ?>">
                                        <?php echo esc_html($role->post_title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Back to Category -->
                    <?php if ($category) : ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="200">
                        <a href="<?php echo get_term_link($category); ?>" class="btn btn-outline btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <?php printf(__('Back to %s', 'haupt-recruitment'), $category->name); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                </aside>
            </div>
        </div>
    </div>
</article>

<!-- Related Job Roles Section -->
<?php if (!empty($related_roles)) : ?>
<section class="section" id="related-roles">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Explore More', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Related Job Roles', 'haupt-recruitment'); ?></h2>
        </div>
        
        <div class="grid grid-3">
            <?php foreach ($related_roles as $index => $role) : 
                $role_salary = haupt_get_meta('salary_range', $role->ID);
                $role_experience = haupt_get_meta('experience_level', $role->ID);
            ?>
                <article class="card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <?php if (has_post_thumbnail($role->ID)) : ?>
                        <div class="card-image">
                            <a href="<?php echo get_permalink($role->ID); ?>">
                                <?php echo get_the_post_thumbnail($role->ID, 'card'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="card-content">
                        <h3 class="card-title">
                            <a href="<?php echo get_permalink($role->ID); ?>"><?php echo esc_html($role->post_title); ?></a>
                        </h3>
                        <div class="card-meta">
                            <?php if ($role_salary) : ?>
                                <span class="card-meta-item"><?php echo esc_html($role_salary); ?></span>
                            <?php endif; ?>
                            <?php if ($role_experience) : ?>
                                <span class="card-meta-item"><?php echo esc_html($role_experience); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($role->post_excerpt) : ?>
                            <p class="card-text"><?php echo esc_html($role->post_excerpt); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo get_permalink($role->ID); ?>" class="btn btn-sm btn-outline">
                            <?php _e('Read Guide', 'haupt-recruitment'); ?>
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
