<?php
/**
 * Single Post Template
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

while (have_posts()) :
    the_post();
    ?>
    
    <!-- Article Header -->
    <header class="article-header">
        <div class="article-header-bg">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('hero', ['alt' => get_the_title()]); ?>
            <?php else : ?>
                <img src="<?php echo HAUPT_URI; ?>/assets/images/default-hero.jpg" alt="" aria-hidden="true">
            <?php endif; ?>
        </div>
        <div class="article-header-overlay"></div>
        <div class="article-header-content">
            <div class="article-meta-top">
                <span class="article-category"><?php the_category(', '); ?></span>
                <span class="article-date"><?php echo get_the_date(); ?></span>
            </div>
            <h1 class="article-title"><?php the_title(); ?></h1>
            <div class="article-author">
                <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', ['class' => 'author-avatar']); ?>
                <div class="author-info">
                    <span class="author-name"><?php the_author(); ?></span>
                    <span class="author-role"><?php the_author_meta('description'); ?></span>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Breadcrumbs -->
    <?php echo haupt_get_breadcrumbs(); ?>
    
    <!-- Article Content -->
    <article id="post-<?php the_ID(); ?>" <?php post_class('article-single'); ?>>
        <div class="section">
            <div class="container">
                <div class="article-layout">
                    <!-- Main Content -->
                    <div class="article-main">
                        <div class="content entry-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Tags -->
                        <?php if (has_tag()) : ?>
                            <div class="article-tags">
                                <span class="tags-label"><?php _e('Tags:', 'haupt-recruitment'); ?></span>
                                <?php the_tags('', '', ''); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Share -->
                        <div class="article-share">
                            <span class="share-label"><?php _e('Share:', 'haupt-recruitment'); ?></span>
                            <div class="share-buttons">
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn linkedin" aria-label="Share on LinkedIn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" aria-label="Share on Twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-btn facebook" aria-label="Share on Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <button class="share-btn copy" aria-label="Copy link" data-url="<?php the_permalink(); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Author Box -->
                        <div class="author-box">
                            <?php echo get_avatar(get_the_author_meta('ID'), 120, '', '', ['class' => 'author-box-avatar']); ?>
                            <div class="author-box-content">
                                <h3 class="author-box-name"><?php the_author(); ?></h3>
                                <p class="author-box-bio"><?php the_author_meta('description'); ?></p>
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="btn btn-sm btn-outline">
                                    <?php _e('View All Posts', 'haupt-recruitment'); ?>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Post Navigation -->
                        <nav class="post-navigation">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            <div class="post-nav-links">
                                <?php if ($prev_post) : ?>
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="post-nav-prev">
                                        <span class="post-nav-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                            <?php _e('Previous Article', 'haupt-recruitment'); ?>
                                        </span>
                                        <span class="post-nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($next_post) : ?>
                                    <a href="<?php echo get_permalink($next_post); ?>" class="post-nav-next">
                                        <span class="post-nav-label">
                                            <?php _e('Next Article', 'haupt-recruitment'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                        </span>
                                        <span class="post-nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </nav>
                        
                        <!-- Comments -->
                        <?php
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        }
                        ?>
                        
                        <!-- FAQ Schema -->
                        <?php echo haupt_get_faq_schema(); ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <?php if (is_active_sidebar('blog-sidebar')) : ?>
                        <aside class="article-sidebar">
                            <?php dynamic_sidebar('blog-sidebar'); ?>
                        </aside>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
    
    <!-- Related Posts -->
    <?php
    $related_posts = get_posts([
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
        'category__in' => wp_get_post_categories(get_the_ID()),
    ]);
    
    if (!empty($related_posts)) :
    ?>
    <section class="section section-gray">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Related Articles', 'haupt-recruitment'); ?></h2>
            </div>
            <div class="grid grid-3">
                <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                    <article class="card" data-aos="fade-up">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('card'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline"><?php _e('Read More', 'haupt-recruitment'); ?></a>
                        </div>
                    </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
<?php
endwhile;

get_footer();
