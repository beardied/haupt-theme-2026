<?php
/**
 * Jobs Archive Template - AJAX Live Filtering
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get all filter options
$sectors = get_terms(['taxonomy' => 'job_sector', 'hide_empty' => false]);
$locations = get_terms(['taxonomy' => 'job_location', 'hide_empty' => false]);
$job_types = get_terms(['taxonomy' => 'job_type', 'hide_empty' => false]);
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Career Opportunities', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('Current Vacancies', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Discover your next role in the UK power and energy sector. Use the filters to find your perfect job.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Job Results Section with Sidebar -->
<section class="section" id="job-results-section">
    <div class="container">
        <div class="jobs-layout">
            <!-- Sidebar Filters -->
            <aside class="jobs-sidebar" id="job-filters">
                <!-- Live Search -->
                <div class="sidebar-widget">
                    <h4><?php _e('Search', 'haupt-recruitment'); ?></h4>
                    <div class="form-group">
                        <input type="text" id="filter-keyword" class="form-input" placeholder="<?php esc_attr_e('Job title, keywords...', 'haupt-recruitment'); ?>" autocomplete="off">
                    </div>
                </div>
                
                <!-- Job Sectors -->
                <div class="sidebar-widget">
                    <h4><?php _e('Sectors', 'haupt-recruitment'); ?></h4>
                    <div class="filter-list">
                        <?php foreach ($sectors as $sector) : ?>
                        <label class="filter-item">
                            <input type="checkbox" name="sector[]" value="<?php echo esc_attr($sector->slug); ?>" data-filter="sector">
                            <span class="filter-label"><?php echo esc_html($sector->name); ?> <span class="count">(<?php echo $sector->count; ?>)</span></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Locations -->
                <div class="sidebar-widget">
                    <h4><?php _e('Locations', 'haupt-recruitment'); ?></h4>
                    <div class="filter-list">
                        <?php foreach ($locations as $location) : ?>
                        <label class="filter-item">
                            <input type="checkbox" name="location[]" value="<?php echo esc_attr($location->slug); ?>" data-filter="location">
                            <span class="filter-label"><?php echo esc_html($location->name); ?> <span class="count">(<?php echo $location->count; ?>)</span></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Job Categories -->
                <div class="sidebar-widget">
                    <h4><?php _e('Job Categories', 'haupt-recruitment'); ?></h4>
                    <div class="filter-list">
                        <?php 
                        $categories = get_terms(['taxonomy' => 'job_category', 'hide_empty' => false]);
                        if (!empty($categories) && !is_wp_error($categories)) :
                            foreach ($categories as $category) : 
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" name="category[]" value="<?php echo esc_attr($category->slug); ?>" data-filter="category">
                            <span class="filter-label"><?php echo esc_html($category->name); ?> <span class="count">(<?php echo $category->count; ?>)</span></span>
                        </label>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                
                <!-- Clear Filters -->
                <button type="button" id="clear-filters" class="btn btn-outline btn-sm btn-block" style="display: none;">
                    <?php _e('Clear All Filters', 'haupt-recruitment'); ?>
                </button>
            </aside>
            
            <!-- Main Content -->
            <div class="jobs-main">
                <div class="job-results-header">
                    <h2 class="job-results-count">
                        <span id="job-count"><?php echo wp_count_posts('job')->publish; ?></span> 
                        <?php _e('Jobs Available', 'haupt-recruitment'); ?>
                    </h2>
                </div>
                
                <!-- Loading Indicator -->
                <div id="job-loading" class="job-loading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p><?php _e('Loading jobs...', 'haupt-recruitment'); ?></p>
                </div>
                
                <!-- Job Grid -->
                <div id="job-results" class="job-results">
                    <div class="grid grid-auto" id="job-grid">
                        <?php
                        $jobs_query = new WP_Query([
                            'post_type' => 'job',
                            'posts_per_page' => 12,
                            'paged' => 1,
                        ]);
                        
                        if ($jobs_query->have_posts()) :
                            $delay = 0;
                            while ($jobs_query->have_posts()) : $jobs_query->the_post();
                                $location = haupt_get_meta('job_location');
                                $salary = haupt_get_meta('salary');
                                $job_type = haupt_get_meta('job_type');
                                $job_sectors = get_the_terms(get_the_ID(), 'job_sector');
                        ?>
                            <article class="card job-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('card'); ?>
                                        </a>
                                        <?php if ($job_sectors && !is_wp_error($job_sectors)) : ?>
                                            <span class="card-badge"><?php echo esc_html($job_sectors[0]->name); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
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
                                            <span class="card-meta-item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg>
                                                <?php echo esc_html($job_type); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h3 class="card-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="card-text">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <?php if ($salary) : ?>
                                            <span class="card-salary"><?php echo esc_html($salary); ?></span>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">
                                            <?php _e('View Details', 'haupt-recruitment'); ?>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php
                                $delay += 100;
                                if ($delay > 500) $delay = 0;
                            endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                
                <!-- Load More -->
                <div class="load-more-wrapper" id="load-more-wrapper">
                    <button type="button" id="load-more" class="btn btn-outline" data-page="1">
                        <?php _e('Load More Jobs', 'haupt-recruitment'); ?>
                    </button>
                </div>
                
                <!-- No Results -->
                <div id="no-results" class="no-results" style="display: none;">
                    <div class="no-results-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3><?php _e('No jobs found', 'haupt-recruitment'); ?></h3>
                    <p><?php _e('We currently don\'t have any positions matching your criteria. Try adjusting your filters.', 'haupt-recruitment'); ?></p>
                    <button type="button" id="clear-filters-no-results" class="btn btn-primary">
                        <?php _e('Clear Filters', 'haupt-recruitment'); ?>
                    </button>
                </div>
            </div><!-- /.jobs-main -->
        </div><!-- /.jobs-layout -->
    </div>
</section>


<script>
(function() {
    'use strict';
    
    const jobGrid = document.getElementById('job-grid');
    const jobCount = document.getElementById('job-count');
    const loading = document.getElementById('job-loading');
    const noResults = document.getElementById('no-results');
    const loadMoreBtn = document.getElementById('load-more');
    const loadMoreWrapper = document.getElementById('load-more-wrapper');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const clearFiltersNoResults = document.getElementById('clear-filters-no-results');
    const activeFilters = document.getElementById('active-filters');
    const keywordInput = document.getElementById('filter-keyword');
    
    let currentPage = 1;
    let isLoading = false;
    let searchTimeout;
    
    // Get all filter checkboxes
    function getFilters() {
        const filters = {
            sector: [],
            location: [],
            category: [],
            keyword: keywordInput.value.trim()
        };
        
        document.querySelectorAll('input[data-filter]:checked').forEach(function(checkbox) {
            const filterType = checkbox.dataset.filter;
            if (filters[filterType]) {
                filters[filterType].push(checkbox.value);
            }
        });
        
        return filters;
    }
    
    // Update active filters display - just show/hide clear button
    function updateActiveFilters() {
        const filters = getFilters();
        const hasFilters = filters.sector.length > 0 || filters.location.length > 0 || filters.category.length > 0 || filters.keyword !== '';
        clearFiltersBtn.style.display = hasFilters ? 'block' : 'none';
    }
    
    // Load jobs via AJAX
    function loadJobs(reset = false) {
        if (isLoading) return;
        
        if (reset) {
            currentPage = 1;
            jobGrid.innerHTML = '';
        }
        
        isLoading = true;
        loading.style.display = 'flex';
        noResults.style.display = 'none';
        loadMoreWrapper.style.display = 'none';
        
        const filters = getFilters();
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=haupt_filter_jobs&nonce=<?php echo wp_create_nonce('haupt_jobs_nonce'); ?>&' + 
                  'page=' + currentPage + '&' +
                  'keyword=' + encodeURIComponent(filters.keyword) + '&' +
                  'sector=' + encodeURIComponent(filters.sector.join(',')) + '&' +
                  'location=' + encodeURIComponent(filters.location.join(',')) + '&' +
                  'category=' + encodeURIComponent(filters.category.join(','))
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            loading.style.display = 'none';
            isLoading = false;
            
            if (data.success) {
                jobCount.textContent = data.data.found_posts;
                
                if (reset) {
                    jobGrid.innerHTML = data.data.html;
                } else {
                    jobGrid.insertAdjacentHTML('beforeend', data.data.html);
                }
                
                if (data.data.found_posts === 0) {
                    noResults.style.display = 'block';
                    loadMoreWrapper.style.display = 'none';
                } else if (data.data.has_more) {
                    loadMoreWrapper.style.display = 'block';
                    loadMoreBtn.dataset.page = currentPage;
                } else {
                    loadMoreWrapper.style.display = 'none';
                }
                
                updateActiveFilters();
                
                // Re-init AOS for new elements
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            }
        })
        .catch(function(error) {
            loading.style.display = 'none';
            isLoading = false;
            console.error('Error loading jobs:', error);
        });
    }
    
    // Event listeners
    document.querySelectorAll('input[data-filter]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            loadJobs(true);
        });
    });
    
    keywordInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadJobs(true);
        }, 300);
    });
    
    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        loadJobs(false);
    });
    
    clearFiltersBtn.addEventListener('click', function() {
        document.querySelectorAll('input[data-filter]').forEach(function(cb) { cb.checked = false; });
        keywordInput.value = '';
        loadJobs(true);
    });
    
    if (clearFiltersNoResults) {
        clearFiltersNoResults.addEventListener('click', function() {
            document.querySelectorAll('input[data-filter]').forEach(function(cb) { cb.checked = false; });
            keywordInput.value = '';
            loadJobs(true);
        });
    }
})();
</script>

<?php
get_footer();
