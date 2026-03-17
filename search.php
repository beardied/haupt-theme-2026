<?php
/**
 * Search Results Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

$search_query = get_search_query();
$total_results = $wp_query->found_posts;
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/search-header.jpg" alt="" aria-hidden="true">
    </div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Search Results', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php printf(__('Search: %s', 'haupt-recruitment'), esc_html($search_query)); ?></h1>
        <p class="page-header-description">
            <?php 
            printf(
                _n('Found %s result for your search.', 'Found %s results for your search.', $total_results, 'haupt-recruitment'),
                number_format($total_results)
            ); 
            ?>
        </p>
    </div>
</header>

<!-- Search Form -->
<section class="section section-gray search-form-section">
    <div class="container">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-page-form" data-aos="fade-up">
            <div class="search-page-wrapper">
                <input type="search" name="s" class="search-page-input" value="<?php echo esc_attr($search_query); ?>" placeholder="<?php _e('Search for jobs, articles, or pages...', 'haupt-recruitment'); ?>" required>
                <button type="submit" class="search-page-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <?php _e('Search', 'haupt-recruitment'); ?>
                </button>
            </div>
        </form>
        
        <!-- Filter by Post Type -->
        <div class="search-filters" data-aos="fade-up" data-aos-delay="100">
            <span class="search-filters-label"><?php _e('Filter by:', 'haupt-recruitment'); ?></span>
            <div class="search-filter-buttons">
                <a href="<?php echo esc_url(add_query_arg('post_type', '')); ?>" class="search-filter <?php echo !isset($_GET['post_type']) ? 'active' : ''; ?>">
                    <?php _e('All', 'haupt-recruitment'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('post_type', 'job')); ?>" class="search-filter <?php echo isset($_GET['post_type']) && $_GET['post_type'] === 'job' ? 'active' : ''; ?>">
                    <?php _e('Jobs', 'haupt-recruitment'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('post_type', 'post')); ?>" class="search-filter <?php echo isset($_GET['post_type']) && $_GET['post_type'] === 'post' ? 'active' : ''; ?>">
                    <?php _e('News', 'haupt-recruitment'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('post_type', 'page')); ?>" class="search-filter <?php echo isset($_GET['post_type']) && $_GET['post_type'] === 'page' ? 'active' : ''; ?>">
                    <?php _e('Pages', 'haupt-recruitment'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Search Results -->
<section class="section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="search-results-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('search-result-card'); ?> data-aos="fade-up">
                        <div class="search-result-type">
                            <?php
                            $post_type = get_post_type();
                            $type_labels = [
                                'job' => __('Job', 'haupt-recruitment'),
                                'post' => __('News', 'haupt-recruitment'),
                                'page' => __('Page', 'haupt-recruitment'),
                                'job_role' => __('Career Guide', 'haupt-recruitment'),
                            ];
                            echo esc_html($type_labels[$post_type] ?? __('Content', 'haupt-recruitment'));
                            ?>
                        </div>
                        
                        <h2 class="search-result-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <div class="search-result-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="search-result-link">
                            <?php _e('Read more', 'haupt-recruitment'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
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
            <div class="no-results" data-aos="fade-up">
                <div class="no-results-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h2><?php _e('No results found', 'haupt-recruitment'); ?></h2>
                <p><?php _e('We couldn\'t find any results matching your search. Try different keywords or browse our popular pages.', 'haupt-recruitment'); ?></p>
                
                <div class="no-results-suggestions">
                    <h3><?php _e('Try these instead:', 'haupt-recruitment'); ?></h3>
                    <ul>
                        <li><?php _e('Check your spelling', 'haupt-recruitment'); ?></li>
                        <li><?php _e('Use more general keywords', 'haupt-recruitment'); ?></li>
                        <li><?php _e('Try searching for job titles or locations', 'haupt-recruitment'); ?></li>
                    </ul>
                </div>
                
                <div class="no-results-actions">
                    <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-primary">
                        <?php _e('Browse All Jobs', 'haupt-recruitment'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline">
                        <?php _e('Back to Homepage', 'haupt-recruitment'); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
