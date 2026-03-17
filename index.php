<?php
/**
 * Main Index Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/page-header-bg.jpg" alt="" aria-hidden="true">
    </div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Latest Updates', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('News & Insights', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Stay informed with the latest industry news, career advice, and company updates from Haupt Recruitment.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Blog Content -->
<section class="section">
    <div class="container">
        <div class="blog-layout">
            <!-- Main Content -->
            <div class="blog-main">
                <?php if (have_posts()) : ?>
                    <div class="grid grid-auto">
                        <?php while (have_posts()) : the_post(); ?>
                            <article <?php post_class('card blog-card'); ?> data-aos="fade-up">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('card', ['alt' => get_the_title()]); ?>
                                        </a>
                                        <span class="card-badge"><?php echo get_the_category_list(', '); ?></span>
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
                                        <span class="card-meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                            </svg>
                                            <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
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
                        <p><?php _e('There are no posts to display at this time.', 'haupt-recruitment'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <?php if (is_active_sidebar('blog-sidebar')) : ?>
                <aside class="blog-sidebar">
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
get_footer();
