<?php
/**
 * Role Expertise Archive Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();

// Get all categories for the sidebar
$categories = get_terms([
    'taxonomy' => 'role_expertise_category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

// Check if we're viewing a specific category
$current_category = get_queried_object();
$is_category_view = is_tax('role_expertise_category');
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Career Guides', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title">
            <?php 
            if ($is_category_view && $current_category) {
                echo esc_html($current_category->name);
            } else {
                _e('Role Expertise', 'haupt-recruitment');
            }
            ?>
        </h1>
        <?php if ($archive_description) : ?>
            <p class="page-header-description"><?php echo wp_kses_post($archive_description); ?></p>
        <?php elseif ($is_category_view && $current_category->description) : ?>
            <p class="page-header-description"><?php echo wp_kses_post($current_category->description); ?></p>
        <?php else : ?>
            <p class="page-header-description"><?php _e('Explore our comprehensive guides for engineering and technical roles across the UK power sector.', 'haupt-recruitment'); ?></p>
        <?php endif; ?>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Archive Content -->
<section class="section">
    <div class="container">
        <div class="expert-layout">
            
            <!-- Main Content -->
            <div class="expert-main">
                <?php if (have_posts()) : ?>
                    <div class="grid grid-auto">
                        <?php while (have_posts()) : the_post(); 
                            $salary = haupt_get_meta('salary_range');
                            $experience = haupt_get_meta('experience_level');
                            // Get categories for this post
                            $post_cats = get_the_terms(get_the_ID(), 'role_expertise_category');
                        ?>
                            <article <?php post_class('card'); ?> data-aos="fade-up">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('card', ['alt' => get_the_title()]); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-content">
                                    <?php if (!empty($post_cats) && !is_wp_error($post_cats)) : ?>
                                        <div class="card-category">
                                            <a href="<?php echo get_term_link($post_cats[0]); ?>">
                                                <?php echo esc_html($post_cats[0]->name); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h2 class="card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    
                                    <?php if ($salary || $experience) : ?>
                                        <div class="card-meta">
                                            <?php if ($salary) : ?>
                                                <span class="card-meta-item"><?php echo esc_html($salary); ?></span>
                                            <?php endif; ?>
                                            <?php if ($experience) : ?>
                                                <span class="card-meta-item"><?php echo esc_html($experience); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-text">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline">
                                            <?php _e('Read Guide', 'haupt-recruitment'); ?>
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
                        <h2><?php _e('No guides found', 'haupt-recruitment'); ?></h2>
                        <p><?php _e('There are no role expertise guides to display.', 'haupt-recruitment'); ?></p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <?php _e('Return to Homepage', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar with Categories -->
            <aside class="expert-sidebar">
                
                <!-- Categories Widget -->
                <div class="sidebar-widget" data-aos="fade-left">
                    <h4><?php _e('Role Expertise Categories', 'haupt-recruitment'); ?></h4>
                    
                    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                        <ul class="sidebar-category-list">
                            <?php foreach ($categories as $category) : 
                                $is_active = ($is_category_view && $current_category->term_id === $category->term_id);
                                $count = $category->count;
                            ?>
                                <li class="<?php echo $is_active ? 'active' : ''; ?>">
                                    <a href="<?php echo get_term_link($category); ?>">
                                        <span class="category-name"><?php echo esc_html($category->name); ?></span>
                                        <span class="category-count"><?php echo $count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><?php _e('No categories found.', 'haupt-recruitment'); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- View All Widget -->
                <?php if ($is_category_view) : ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                        <a href="<?php echo get_post_type_archive_link('role_expertise'); ?>" class="btn btn-outline btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <?php _e('View All Role Expertise', 'haupt-recruitment'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- CTA Widget -->
                <div class="sidebar-widget sidebar-widget-highlight" data-aos="fade-left" data-aos-delay="200">
                    <h4><?php _e('Looking for a Role?', 'haupt-recruitment'); ?></h4>
                    <p><?php _e('Browse our current job openings or register your CV with us.', 'haupt-recruitment'); ?></p>
                    <a href="<?php echo get_post_type_archive_link('job'); ?>" class="btn btn-primary btn-block">
                        <?php _e('View Jobs', 'haupt-recruitment'); ?>
                    </a>
                </div>
                
            </aside>
        </div>
    </div>
</section>

<?php
get_footer();
