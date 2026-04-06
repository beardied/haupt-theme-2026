<?php
/**
 * Archive Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Archive', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php echo wp_kses_post($archive_title); ?></h1>
        <?php if ($archive_description) : ?>
            <p class="page-header-description"><?php echo wp_kses_post($archive_description); ?></p>
        <?php endif; ?>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Archive Content -->
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
                            <div class="card-meta">
                                <span class="card-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>
                            </div>
                            
                            <h2 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="card-text">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            
                            <div class="card-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline">
                                    <?php _e('Read More', 'haupt-recruitment'); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                ]);
                ?>
            </div>
            
        <?php else : ?>
            <div class="no-results">
                <h2><?php _e('No posts found', 'haupt-recruitment'); ?></h2>
                <p><?php _e('There are no posts to display in this archive.', 'haupt-recruitment'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php _e('Return to Homepage', 'haupt-recruitment'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
