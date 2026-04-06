<?php
/**
 * Role Expertise Category Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get current category
$current_category = get_queried_object();

// Get all categories for the sidebar
$categories = get_terms([
    'taxonomy' => 'role_expertise_category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

// Get parent categories for breadcrumb chain
$parent_cats = [];
if ($current_category->parent) {
    $parent = $current_category;
    while ($parent->parent) {
        $parent = get_term($parent->parent, 'role_expertise_category');
        if ($parent && !is_wp_error($parent)) {
            $parent_cats[] = $parent;
        }
    }
    $parent_cats = array_reverse($parent_cats);
}

// Get child categories if any
$child_cats = get_terms([
    'taxonomy' => 'role_expertise_category',
    'parent' => $current_category->term_id,
    'hide_empty' => false,
]);
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Career Guides', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php echo esc_html($current_category->name); ?></h1>
        <?php if ($current_category->description) : ?>
            <p class="page-header-description"><?php echo wp_kses_post($current_category->description); ?></p>
        <?php else : ?>
            <p class="page-header-description">
                <?php 
                printf(
                    __('Explore %d role expertise guides in the %s category.', 'haupt-recruitment'),
                    $current_category->count,
                    esc_html($current_category->name)
                ); 
                ?>
            </p>
        <?php endif; ?>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Category Content -->
<section class="section">
    <div class="container">
        <div class="expert-layout">
            
            <!-- Main Content -->
            <div class="expert-main">
                
                <?php if (!empty($child_cats)) : ?>
                    <!-- Subcategories -->
                    <div class="subcategories-section" data-aos="fade-up">
                        <h3><?php _e('Subcategories', 'haupt-recruitment'); ?></h3>
                        <div class="subcategories-grid">
                            <?php foreach ($child_cats as $child) : ?>
                                <a href="<?php echo get_term_link($child); ?>" class="subcategory-card">
                                    <span class="subcategory-name"><?php echo esc_html($child->name); ?></span>
                                    <span class="subcategory-count"><?php echo $child->count; ?> guides</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (have_posts()) : ?>
                    <div class="grid grid-auto">
                        <?php while (have_posts()) : the_post(); 
                            $salary = haupt_get_meta('salary_range');
                            $experience = haupt_get_meta('experience_level');
                            // Get all categories for this post
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
                                            <?php 
                                            // Show categories except current
                                            $other_cats = array_filter($post_cats, function($cat) use ($current_category) {
                                                return $cat->term_id !== $current_category->term_id;
                                            });
                                            if (!empty($other_cats)) {
                                                $first_other = array_values($other_cats)[0];
                                                echo '<a href="' . get_term_link($first_other) . '">' . esc_html($first_other->name) . '</a>';
                                            } else {
                                                echo '<span>' . esc_html($current_category->name) . '</span>';
                                            }
                                            ?>
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
                        <p><?php _e('There are no role expertise guides in this category yet.', 'haupt-recruitment'); ?></p>
                        <a href="<?php echo get_post_type_archive_link('role_expertise'); ?>" class="btn btn-primary">
                            <?php _e('View All Role Expertise', 'haupt-recruitment'); ?>
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
                                $is_active = ($current_category->term_id === $category->term_id);
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
                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                    <a href="<?php echo get_post_type_archive_link('role_expertise'); ?>" class="btn btn-outline btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <?php _e('View All Role Expertise', 'haupt-recruitment'); ?>
                    </a>
                </div>
                
                <!-- Parent Category Widget (if viewing subcategory) -->
                <?php if (!empty($parent_cats)) : 
                    $immediate_parent = end($parent_cats);
                ?>
                    <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="150">
                        <a href="<?php echo get_term_link($immediate_parent); ?>" class="btn btn-outline btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <?php printf(__('Back to %s', 'haupt-recruitment'), $immediate_parent->name); ?>
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
