<?php
/**
 * Template Name: Expert Hub Page
 * Description: Hub page for expert content - displays child spoke pages
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get child pages (spokes)
    $spoke_pages = get_pages([
        'child_of' => get_the_ID(),
        'parent' => get_the_ID(),
        'sort_column' => 'menu_order',
        'hierarchical' => 0,
    ]);
    
    // Get related job sectors
    $related_sectors = null; // Define sectors taxonomy query here if needed
?>

<!-- Page Header -->
<header class="page-header page-header-hub">
    <div class="page-header-bg">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('hero'); ?>
        <?php else : ?>
            <img src="<?php echo HAUPT_URI; ?>/assets/images/expert-hub-bg.jpg" alt="">
        <?php endif; ?>
    </div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Expert Knowledge Hub', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?>
            <p class="page-header-description"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Hub Introduction -->
<section class="section">
    <div class="container">
        <div class="hub-intro">
            <div class="content" data-aos="fade-up">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>

<!-- Spoke Pages Grid -->
<?php if (!empty($spoke_pages)) : ?>
<section class="section section-gray" id="spokes">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('In-Depth Guides', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Explore Related Topics', 'haupt-recruitment'); ?></h2>
        </div>
        
        <div class="grid grid-auto">
            <?php foreach ($spoke_pages as $index => $spoke) : 
                $spoke_icon = null;
                $reading_time = '5 min read';
            ?>
                <article class="spoke-card" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                    <?php if (has_post_thumbnail($spoke->ID)) : ?>
                        <div class="spoke-card-image">
                            <a href="<?php echo get_permalink($spoke->ID); ?>">
                                <?php echo get_the_post_thumbnail($spoke->ID, 'card'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="spoke-card-content">
                        <?php if ($spoke_icon) : ?>
                            <div class="spoke-card-icon">
                                <img src="<?php echo esc_url($spoke_icon['url']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                        
                        <div class="spoke-card-meta">
                            <span class="reading-time">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <?php echo esc_html($reading_time); ?>
                            </span>
                        </div>
                        
                        <h3 class="spoke-card-title">
                            <a href="<?php echo get_permalink($spoke->ID); ?>"><?php echo esc_html($spoke->post_title); ?></a>
                        </h3>
                        
                        <?php if ($spoke->post_excerpt) : ?>
                            <p class="spoke-card-excerpt"><?php echo esc_html($spoke->post_excerpt); ?></p>
                        <?php endif; ?>
                        
                        <a href="<?php echo get_permalink($spoke->ID); ?>" class="spoke-card-link">
                            <?php _e('Read Guide', 'haupt-recruitment'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Related Jobs -->
<?php if ($related_sectors) : ?>
<section class="section" id="related-jobs">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Current Opportunities', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Related Job Vacancies', 'haupt-recruitment'); ?></h2>
        </div>
        
        <?php
        $sector_ids = array_map(function($sector) { return $sector->term_id; }, $related_sectors);
        $related_jobs = new WP_Query([
            'post_type' => 'job',
            'posts_per_page' => 4,
            'tax_query' => [
                [
                    'taxonomy' => 'job_sector',
                    'field' => 'term_id',
                    'terms' => $sector_ids,
                ],
            ],
        ]);
        
        if ($related_jobs->have_posts()) :
        ?>
            <div class="grid grid-auto">
                <?php while ($related_jobs->have_posts()) : $related_jobs->the_post(); ?>
                    <article class="card job-card" data-aos="fade-up">
                        <div class="card-content">
                            <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline"><?php _e('View Job', 'haupt-recruitment'); ?></a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- FAQ Section -->
<?php 
// FAQ Section - Add FAQ using Gutenberg blocks in page content
?>

<?php
endwhile;

get_footer();
