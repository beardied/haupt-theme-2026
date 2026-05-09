<?php
/**
 * Taxonomy Template - Redirects to main jobs archive
 * All filtering is now done via AJAX on the main /jobs/ page
 *
 * @package Haupt_Recruitment_2026
 */

$queried_object = get_queried_object();

// If this is a job-related taxonomy, redirect to main jobs page
if ($queried_object && in_array($queried_object->taxonomy, ['job_sector', 'job_location', 'job_type', 'job_category'])) {
    $jobs_url = get_post_type_archive_link('job');
    
    // Map taxonomy to query parameter
    $param_map = [
        'job_sector' => 'sector',
        'job_location' => 'location',
        'job_type' => 'type',
        'job_category' => 'category',
    ];
    
    if (isset($param_map[$queried_object->taxonomy])) {
        $jobs_url = add_query_arg($param_map[$queried_object->taxonomy], $queried_object->slug, $jobs_url);
    }
    
    wp_redirect($jobs_url, 301);
    exit;
}

// For other taxonomies, use default archive template
get_header();
$archive_title = get_the_archive_title();
?>

<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Archive', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php echo wp_kses_post($archive_title); ?></h1>
    </div>
</header>

<?php echo haupt_get_breadcrumbs(); ?>

<section class="section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="grid grid-auto">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('card'); ?> data-aos="fade-up">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('card', ['alt' => get_the_title()]); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="pagination">
                <?php the_posts_pagination(['mid_size' => 2]); ?>
            </div>
        <?php else : ?>
            <div class="no-results">
                <h2><?php _e('No posts found', 'haupt-recruitment'); ?></h2>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
