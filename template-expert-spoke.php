<?php
/**
 * Template Name: Expert Spoke Page
 * Description: Individual expert content page with FAQ and related links
 * DEPRECATED: Use template-job-role-expert.php instead for job role guides
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get parent hub page
    $parent_id = wp_get_post_parent_id(get_the_ID());
    $parent = $parent_id ? get_post($parent_id) : null;
    
    // Get related content
    $related_spokes = get_pages([
        'child_of' => $parent_id,
        'parent' => $parent_id,
        'exclude' => get_the_ID(),
        'number' => 3,
    ]);
    
    $reading_time = '5 min read';
    $last_updated = get_the_modified_date('j F Y');
?>

<!-- Article Header -->
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
            <?php if ($parent) : ?>
                <nav class="expert-parent-nav" data-aos="fade-down">
                    <a href="<?php echo get_permalink($parent->ID); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <?php echo esc_html($parent->post_title); ?>
                    </a>
                </nav>
            <?php endif; ?>
            
            <span class="expert-label" data-aos="fade-up"><?php _e('Expert Guide', 'haupt-recruitment'); ?></span>
            <h1 class="expert-title" data-aos="fade-up" data-aos-delay="100"><?php the_title(); ?></h1>
            
            <div class="expert-meta" data-aos="fade-up" data-aos-delay="200">
                <span class="expert-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo esc_html($reading_time); ?>
                </span>
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

<!-- Expert Content -->
<article class="expert-article">
    <div class="section">
        <div class="container">
            <div class="expert-layout">
                <!-- Main Content -->
                <div class="expert-main">
                    <div class="expert-content content" data-aos="fade-up">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php if (false) : // Table of contents - implement with JS or custom meta ?>
                    <div class="expert-toc" data-aos="fade-up">
                        <h3><?php _e('On this page', 'haupt-recruitment'); ?></h3>
                        <ul id="toc-list"></ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- CTA Box -->
                    <div class="expert-cta-box" data-aos="fade-up">
                        <h3><?php _e('Looking for opportunities in this field?', 'haupt-recruitment'); ?></h3>
                        <p><?php _e('Browse our current vacancies or register your CV to be notified of new roles.', 'haupt-recruitment'); ?></p>
                        <div class="expert-cta-buttons">
                            <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-primary">
                                <?php _e('Browse Jobs', 'haupt-recruitment'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/upload-cv/')); ?>" class="btn btn-outline">
                                <?php _e('Register CV', 'haupt-recruitment'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if ($tags) :
                    ?>
                    <div class="expert-tags" data-aos="fade-up">
                        <span class="tags-label"><?php _e('Tags:', 'haupt-recruitment'); ?></span>
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag"><?php echo esc_html($tag->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="expert-sidebar">
                    <!-- Quick Contact -->
                    <div class="sidebar-widget" data-aos="fade-left">
                        <h4><?php _e('Speak to an Expert', 'haupt-recruitment'); ?></h4>
                        <p><?php _e('Our specialist consultants are here to help with your recruitment needs.', 'haupt-recruitment'); ?></p>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', haupt_get_phone())); ?>" class="sidebar-phone">
                            <?php echo esc_html(haupt_get_phone()); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-block">
                            <?php _e('Contact Us', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                    
                    <!-- Related Guides -->
                    <?php if (!empty($related_spokes)) : ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                        <h4><?php _e('Related Guides', 'haupt-recruitment'); ?></h4>
                        <ul class="sidebar-links">
                            <?php foreach ($related_spokes as $spoke) : ?>
                                <li>
                                    <a href="<?php echo get_permalink($spoke->ID); ?>">
                                        <?php echo esc_html($spoke->post_title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Back to Hub -->
                    <?php if ($parent) : ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="200">
                        <a href="<?php echo get_permalink($parent->ID); ?>" class="btn btn-outline btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <?php printf(__('Back to %s', 'haupt-recruitment'), $parent->post_title); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</article>

<?php 
// FAQ Section - Add FAQ using Gutenberg blocks in page content
?>

<!-- Related Content -->
<?php if (!empty($related_spokes)) : ?>
<section class="section" id="related-content">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Continue Learning', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Related Guides', 'haupt-recruitment'); ?></h2>
        </div>
        
        <div class="grid grid-3">
            <?php foreach ($related_spokes as $index => $spoke) : ?>
                <article class="card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <?php if (has_post_thumbnail($spoke->ID)) : ?>
                        <div class="card-image">
                            <a href="<?php echo get_permalink($spoke->ID); ?>">
                                <?php echo get_the_post_thumbnail($spoke->ID, 'card'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="card-content">
                        <h3 class="card-title">
                            <a href="<?php echo get_permalink($spoke->ID); ?>"><?php echo esc_html($spoke->post_title); ?></a>
                        </h3>
                        <?php if ($spoke->post_excerpt) : ?>
                            <p class="card-text"><?php echo esc_html($spoke->post_excerpt); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo get_permalink($spoke->ID); ?>" class="btn btn-sm btn-outline">
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
