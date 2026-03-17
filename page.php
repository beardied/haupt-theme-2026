<?php
/**
 * Default Page Template
 * Fully Gutenberg compatible with full-width support
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get page options
    $hide_header = haupt_get_meta('hide_page_header', null, false);
    $transparent_header = haupt_get_meta('transparent_header', null, false);
    $header_image = get_the_post_thumbnail_url(get_the_ID(), 'hero');
    ?>
    
    <?php if (!$hide_header) : ?>
    <!-- Page Header -->
    <header class="page-header <?php echo $header_image ? 'has-image' : ''; ?>" 
            <?php if ($header_image) echo 'style="background-image: url(' . esc_url($header_image) . '); background-size: cover; background-position: center;"'; ?>>
        <?php if ($header_image) : ?>
        <div class="page-header-overlay"></div>
        <?php endif; ?>
        <div class="page-header-content">
            <span class="page-header-label"><?php _e('Haupt Recruitment', 'haupt-recruitment'); ?></span>
            <h1 class="page-header-title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="page-header-description"><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- Breadcrumbs (if not homepage) -->
    <?php if (!is_front_page()) echo haupt_get_breadcrumbs(); ?>
    <?php endif; ?>
    
    <!-- Page Content -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('gutenberg-page'); ?>>
        
        <?php if (has_blocks()) : ?>
            <!-- Gutenberg Content -->
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        <?php else : ?>
            <!-- Classic Editor Content -->
            <div class="section">
                <div class="container">
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                    
                    <?php
                    // Page links for paginated content
                    wp_link_pages([
                        'before' => '<div class="page-links">',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                    ]);
                    ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Display child pages if any
        $child_pages = get_pages([
            'child_of' => get_the_ID(),
            'parent' => get_the_ID(),
            'sort_column' => 'menu_order',
        ]);
        
        if (!empty($child_pages)) :
        ?>
        <section class="section section-gray child-pages-section">
            <div class="container">
                <h2 class="section-title text-center"><?php _e('Related Pages', 'haupt-recruitment'); ?></h2>
                <div class="grid grid-auto">
                    <?php foreach ($child_pages as $child) : ?>
                        <div class="card" data-aos="fade-up">
                            <?php if (has_post_thumbnail($child->ID)) : ?>
                                <div class="card-image">
                                    <a href="<?php echo get_permalink($child->ID); ?>">
                                        <?php echo get_the_post_thumbnail($child->ID, 'card'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="<?php echo get_permalink($child->ID); ?>"><?php echo esc_html($child->post_title); ?></a>
                                </h3>
                                <?php if ($child->post_excerpt) : ?>
                                    <p class="card-text"><?php echo esc_html($child->post_excerpt); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_permalink($child->ID); ?>" class="btn btn-sm btn-outline">
                                    <?php _e('Learn More', 'haupt-recruitment'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
    </article>
    
    <?php
    // Comments
    if (comments_open() || get_comments_number()) :
    ?>
    <section class="section section-gray">
        <div class="container container-narrow">
            <?php comments_template(); ?>
        </div>
    </section>
    <?php endif; ?>
    
<?php
endwhile;

get_footer();
