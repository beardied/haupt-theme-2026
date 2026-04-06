<?php
/**
 * Template Name: Homepage
 * Description: The main homepage template with hero, stats, featured content, and CTA sections
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get ACF fields
$hero_title = haupt_get_hero('title');
$hero_description = haupt_get_hero('description');
$hero_video = null; // Background video - use featured image instead
$hero_image = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'hero') : null;

// Stats
$stat_placements = haupt_get_stat('placements');
$stat_clients = haupt_get_stat('clients');
$stat_candidates = haupt_get_stat('candidates');
$stat_years = haupt_get_stat('years');
?>

<!-- Hero Section -->
<section class="hero" id="hero">
    <div class="hero-bg">
        <?php if ($hero_video) : ?>
            <video autoplay muted loop playsinline poster="<?php echo esc_url($hero_image['url'] ?? ''); ?>">
                <source src="<?php echo esc_url($hero_video['url']); ?>" type="<?php echo esc_attr($hero_video['mime_type']); ?>">
            </video>
        <?php elseif ($hero_image) : ?>
            <img src="<?php echo esc_url($hero_image['url']); ?>" alt="" aria-hidden="true">
        <?php else : ?>
            <div style="background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 100%); width: 100%; height: 100%;"></div>
        <?php endif; ?>
    </div>
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <span class="hero-label" data-aos="fade-right"><?php _e('UK Power & Energy Specialists', 'haupt-recruitment'); ?></span>
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
            <?php echo wp_kses_post($hero_title); ?>
        </h1>
        <p class="hero-description" data-aos="fade-up" data-aos-delay="200">
            <?php echo esc_html($hero_description); ?>
        </p>
        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="300">
            <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-primary btn-lg btn-magnetic">
                <?php _e('Find Your Next Role', 'haupt-recruitment'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="<?php echo esc_url(home_url('/employer-contact/')); ?>" class="btn btn-ghost btn-lg btn-magnetic">
                <?php _e('I\'m Hiring', 'haupt-recruitment'); ?>
            </a>
        </div>
        
        <!-- Career Guides Link -->
        <div class="hero-secondary-link" data-aos="fade-up" data-aos-delay="350">
            <a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>" class="hero-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <?php _e('Or browse our Career Guides', 'haupt-recruitment'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>
        
        <!-- Hero Stats -->
        <div class="hero-stats" data-aos="fade-up" data-aos-delay="400">
            <div class="hero-stat">
                <div class="hero-stat-number"><?php echo number_format($stat_placements); ?></div>
                <div class="hero-stat-label"><?php _e('Placements Made', 'haupt-recruitment'); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number"><?php echo number_format($stat_clients); ?></div>
                <div class="hero-stat-label"><?php _e('Client Companies', 'haupt-recruitment'); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number"><?php echo number_format($stat_candidates); ?></div>
                <div class="hero-stat-label"><?php _e('Candidates', 'haupt-recruitment'); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number"><?php echo number_format($stat_years); ?></div>
                <div class="hero-stat-label"><?php _e('Years Experience', 'haupt-recruitment'); ?></div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="hero-scroll">
        <span><?php _e('Scroll', 'haupt-recruitment'); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>
</section>

<!-- Sectors Section -->
<section class="section section-gray" id="sectors">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Our Sectors', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Industries We Power', 'haupt-recruitment'); ?></h2>
            <p class="section-description"><?php _e('Specialist recruitment expertise across the UK\'s critical energy infrastructure sectors.', 'haupt-recruitment'); ?></p>
        </div>
        
        <div class="grid grid-auto">
            <?php
            $sectors = get_terms([
                'taxonomy' => 'job_sector',
                'hide_empty' => false,
                'number' => 6,
            ]);
            
            if (!empty($sectors) && !is_wp_error($sectors)) :
                foreach ($sectors as $index => $sector) :
                    // Sector icon and image - use term meta if available
                    $sector_icon = get_term_meta($sector->term_id, 'sector_icon', true);
                    $sector_image = get_term_meta($sector->term_id, 'sector_image', true);
            ?>
                <div class="sector-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="sector-card-image">
                        <?php if ($sector_image) : ?>
                            <img src="<?php echo esc_url($sector_image['url']); ?>" alt="<?php echo esc_attr($sector->name); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="sector-card-content">
                        <?php if ($sector_icon) : ?>
                            <div class="sector-card-icon">
                                <img src="<?php echo esc_url($sector_icon['url']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <h3 class="sector-card-title"><?php echo esc_html($sector->name); ?></h3>
                        <p class="sector-card-description"><?php echo esc_html($sector->description); ?></p>
                        <a href="<?php echo esc_url(get_term_link($sector)); ?>" class="sector-card-link">
                            <?php _e('Explore Roles', 'haupt-recruitment'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php 
                endforeach;
            else :
                // Fallback sectors
                $fallback_sectors = [
                    ['name' => 'Power Infrastructure', 'icon' => 'zap', 'desc' => 'Substations, distribution and transmission lines across the UK power network.'],
                    ['name' => 'Wind Energy', 'icon' => 'wind', 'desc' => 'Onshore and offshore wind farm construction, maintenance and operations.'],
                    ['name' => 'Offshore', 'icon' => 'anchor', 'desc' => 'Offshore oil, gas and renewable energy platform specialists.'],
                    ['name' => 'HV & Cable', 'icon' => 'cpu', 'desc' => 'High voltage installations and cable laying expertise.'],
                    ['name' => 'Renewables', 'icon' => 'sun', 'desc' => 'Solar, battery storage and emerging green energy technologies.'],
                    ['name' => 'Utilities', 'icon' => 'droplet', 'desc' => 'Water, gas and multi-utility infrastructure projects.'],
                ];
                foreach ($fallback_sectors as $index => $sector) :
            ?>
                <div class="sector-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="sector-card-content">
                        <h3 class="sector-card-title"><?php echo esc_html($sector['name']); ?></h3>
                        <p class="sector-card-description"><?php echo esc_html($sector['desc']); ?></p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="sector-card-link">
                            <?php _e('Explore Roles', 'haupt-recruitment'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- Career Guides Section -->
<section class="section" id="career-guides">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Expert Insights', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Career Guides', 'haupt-recruitment'); ?></h2>
            <p class="section-description"><?php _e('In-depth guides to help you navigate career opportunities across the UK power and energy sector.', 'haupt-recruitment'); ?></p>
        </div>
        
        <?php
        // Get featured career guides
        $career_guides = get_posts([
            'post_type' => 'role_expertise',
            'posts_per_page' => 4,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);
        
        if (!empty($career_guides)) :
        ?>
            <div class="grid grid-auto">
                <?php foreach ($career_guides as $index => $guide) : 
                    $salary = haupt_get_meta('salary_range', $guide->ID);
                    $experience = haupt_get_meta('experience_level', $guide->ID);
                    $guide_cats = get_the_terms($guide->ID, 'role_expertise_category');
                ?>
                    <article class="card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <?php if (has_post_thumbnail($guide->ID)) : ?>
                            <div class="card-image">
                                <a href="<?php echo get_permalink($guide->ID); ?>">
                                    <?php echo get_the_post_thumbnail($guide->ID, 'card', ['alt' => $guide->post_title]); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-content">
                            <?php if (!empty($guide_cats) && !is_wp_error($guide_cats)) : ?>
                                <div class="card-category">
                                    <a href="<?php echo get_term_link($guide_cats[0]); ?>">
                                        <?php echo esc_html($guide_cats[0]->name); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="card-title">
                                <a href="<?php echo get_permalink($guide->ID); ?>"><?php echo esc_html($guide->post_title); ?></a>
                            </h3>
                            
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
                                <?php echo wp_trim_words($guide->post_excerpt ?: get_the_excerpt($guide), 20); ?>
                            </div>
                            
                            <div class="card-footer">
                                <a href="<?php echo get_permalink($guide->ID); ?>" class="btn btn-sm btn-outline">
                                    <?php _e('Read Guide', 'haupt-recruitment'); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <div class="section-cta" data-aos="fade-up">
                <a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>" class="btn btn-primary btn-lg">
                    <?php _e('View All Career Guides', 'haupt-recruitment'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
            
        <?php else : ?>
            
            <div class="career-guides-intro" data-aos="fade-up">
                <div class="career-guides-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <h3><?php _e('Comprehensive Career Resources', 'haupt-recruitment'); ?></h3>
                    <p><?php _e('We\'re building detailed career guides for every role in the power and energy sector. From substation engineers to offshore technicians, our guides cover qualifications, career progression, salary expectations, and industry insights.', 'haupt-recruitment'); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>" class="btn btn-primary">
                        <?php _e('Explore Career Guides', 'haupt-recruitment'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section" id="why-us">
    <div class="container">
        <div class="why-us-layout">
            <div class="why-us-content" data-aos="fade-right">
                <span class="section-label"><?php _e('Why Haupt Recruitment', 'haupt-recruitment'); ?></span>
                <h2 class="section-title"><?php _e('The Energy Sector\'s Trusted Recruitment Partner', 'haupt-recruitment'); ?></h2>
                <p class="why-us-lead"><?php _e('We don\'t just fill positions—we build lasting partnerships that power the UK\'s energy infrastructure.', 'haupt-recruitment'); ?></p>
                
                <div class="features-list">
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4><?php _e('Industry Specialists', 'haupt-recruitment'); ?></h4>
                            <p><?php _e('Deep sector knowledge gained from years of exclusive focus on power and energy recruitment.', 'haupt-recruitment'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4><?php _e('Rapid Response', 'haupt-recruitment'); ?></h4>
                            <p><?php _e('Fast turnaround on urgent requirements without compromising on candidate quality.', 'haupt-recruitment'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4><?php _e('Verified Talent Pool', 'haupt-recruitment'); ?></h4>
                            <p><?php _e('Rigorous screening and qualification process ensures only the best candidates.', 'haupt-recruitment'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4><?php _e('Nationwide Coverage', 'haupt-recruitment'); ?></h4>
                            <p><?php _e('UK-wide reach with local expertise to support projects anywhere in the country.', 'haupt-recruitment'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="why-us-image" data-aos="fade-left">
                <img src="<?php echo HAUPT_URI; ?>/assets/images/hv-substation-workers.png" alt="High voltage electrical engineers working on substation equipment wearing personal protective equipment">
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Section -->
<section class="counter-section">
    <div class="container">
        <div class="counter-grid">
            <div class="counter-item" data-aos="fade-up" data-aos-delay="0">
                <div class="counter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="counter-number" data-target="<?php echo esc_attr($stat_placements); ?>">0</div>
                <div class="counter-label"><?php _e('Successful Placements', 'haupt-recruitment'); ?></div>
            </div>
            
            <div class="counter-item" data-aos="fade-up" data-aos-delay="100">
                <div class="counter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    </svg>
                </div>
                <div class="counter-number" data-target="<?php echo esc_attr($stat_clients); ?>">0</div>
                <div class="counter-label"><?php _e('Client Partners', 'haupt-recruitment'); ?></div>
            </div>
            
            <div class="counter-item" data-aos="fade-up" data-aos-delay="200">
                <div class="counter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="counter-number" data-target="<?php echo esc_attr($stat_candidates); ?>">0</div>
                <div class="counter-label"><?php _e('Qualified Candidates', 'haupt-recruitment'); ?></div>
            </div>
            
            <div class="counter-item" data-aos="fade-up" data-aos-delay="300">
                <div class="counter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="counter-number" data-target="<?php echo esc_attr(haupt_get_stat('retention')); ?>" data-suffix="%">0</div>
                <div class="counter-label"><?php _e('Client Retention Rate', 'haupt-recruitment'); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section class="section" id="latest-jobs">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Current Opportunities', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Featured Positions', 'haupt-recruitment'); ?></h2>
            <p class="section-description"><?php _e('Discover your next career move in the UK power and energy sector.', 'haupt-recruitment'); ?></p>
        </div>
        
        <div class="grid grid-auto">
            <?php
            $featured_jobs = new WP_Query([
                'post_type' => 'job',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC',
            ]);
            
            if ($featured_jobs->have_posts()) :
                $delay = 0;
                while ($featured_jobs->have_posts()) : $featured_jobs->the_post();
                    $location = haupt_get_meta('job_location');
                    $salary = haupt_get_meta('salary');
                    $job_type = haupt_get_meta('job_type');
            ?>
                <article class="card job-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="card-content">
                        <div class="card-meta">
                            <?php if ($location) : ?>
                                <span class="card-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <?php echo esc_html($location); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($job_type) : ?>
                                <span class="card-meta-item"><?php echo esc_html($job_type); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="card-text">
                            <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                        </div>
                        <div class="card-footer">
                            <?php if ($salary) : ?>
                                <span class="card-salary"><?php echo esc_html($salary); ?></span>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">
                                <?php _e('View Job', 'haupt-recruitment'); ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php 
                $delay += 100;
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <div class="no-jobs" data-aos="fade-up">
                    <p><?php _e('No current vacancies. Please check back soon or register your CV for future opportunities.', 'haupt-recruitment'); ?></p>
                    <a href="<?php echo esc_url(home_url('/register-with-us/')); ?>" class="btn btn-primary">
                        <?php _e('Register Your CV', 'haupt-recruitment'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-8" data-aos="fade-up">
            <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-secondary btn-lg">
                <?php _e('View All Jobs', 'haupt-recruitment'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section section-gray" id="testimonials">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Testimonials', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('What Our Partners Say', 'haupt-recruitment'); ?></h2>
        </div>
        
        <div class="grid grid-2">
            <?php
            $testimonials = get_posts([
                'post_type' => 'testimonial',
                'posts_per_page' => 4,
                'orderby' => 'rand',
            ]);
            
            if (!empty($testimonials)) :
                foreach ($testimonials as $index => $testimonial) :
                    $content = $testimonial->post_content;
                    $author = haupt_get_meta('testimonial_author', $testimonial->ID);
                    $role = haupt_get_meta('testimonial_role', $testimonial->ID);
                    $company = haupt_get_meta('testimonial_company', $testimonial->ID);
            ?>
                <div class="testimonial" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="testimonial-text"><?php echo wp_kses_post($content); ?></div>
                    <div class="testimonial-author">
                        <div class="testimonial-info">
                            <span class="testimonial-name"><?php echo esc_html($author ?: 'Anonymous'); ?></span>
                            <span class="testimonial-role"><?php echo esc_html($role); ?><?php if ($company) echo ', ' . esc_html($company); ?></span>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            else :
                // Fallback testimonials
                $fallback_testimonials = [
                    ['text' => 'Haupt Recruitment have been instrumental in helping us scale our operations. Their understanding of the power sector is unmatched, and they consistently deliver high-quality candidates.', 'name' => 'Operations Director', 'role' => 'Leading UK Contractor'],
                    ['text' => 'I was impressed by how quickly Haupt understood my career goals and matched me with the perfect role. Their industry knowledge made all the difference.', 'name' => 'Senior Project Manager', 'role' => 'Wind Energy Sector'],
                    ['text' => 'Working with Haupt has transformed our recruitment process. They understand the urgency of our projects and always deliver within tight timeframes.', 'name' => 'HR Director', 'role' => 'Infrastructure Company'],
                    ['text' => 'The team at Haupt genuinely care about finding the right fit. They took the time to understand my skills and experience, leading to a fantastic career opportunity.', 'name' => 'Electrical Engineer', 'role' => 'HV Specialist'],
                ];
                foreach ($fallback_testimonials as $index => $testimonial) :
            ?>
                <div class="testimonial" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="testimonial-text"><?php echo esc_html($testimonial['text']); ?></div>
                    <div class="testimonial-author">
                        <div class="testimonial-info">
                            <span class="testimonial-name"><?php echo esc_html($testimonial['name']); ?></span>
                            <span class="testimonial-role"><?php echo esc_html($testimonial['role']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section section-dark cta-section" id="cta">
    <div class="container">
        <div class="cta-content" data-aos="zoom-in">
            <h2 class="cta-title"><?php _e('Ready to Power Your Next Move?', 'haupt-recruitment'); ?></h2>
            <p class="cta-text"><?php _e('Whether you\'re looking for your dream role or need to build your dream team, we\'re here to make it happen.', 'haupt-recruitment'); ?></p>
            <div class="cta-buttons">
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('candidates'))); ?>" class="btn btn-primary btn-lg">
                    <?php _e('I\'m Looking for Work', 'haupt-recruitment'); ?>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('employers'))); ?>" class="btn btn-white btn-lg">
                    <?php _e('I\'m Looking to Hire', 'haupt-recruitment'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
