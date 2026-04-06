<?php
/**
 * Template Name: Contact Page
 * Description: Contact page with form and company details
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get offices
$offices = haupt_get_offices();
$has_offices = !empty($offices);

// Get first office details for fallback
$first_office = $has_offices ? $offices[0] : null;
$phone = $first_office ? ($first_office['phone'] ?? '') : '';
$email = $first_office ? ($first_office['email'] ?? '') : '';

$map_embed = '';

// Check if coming from a job page
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$job_title = '';
$is_job_enquiry = false;

if ($job_id) {
    $job_post = get_post($job_id);
    if ($job_post && $job_post->post_type === 'job') {
        $job_title = $job_post->post_title;
        $is_job_enquiry = true;
    }
}
?>

<!-- Page Header -->
<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Get in Touch', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('Contact Us', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Ready to take the next step in your career or find the perfect candidate? Our team is here to help.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<!-- Breadcrumbs -->
<?php echo haupt_get_breadcrumbs(); ?>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <div class="contact-layout">
            <!-- Contact Info -->
            <div class="contact-info" data-aos="fade-right">
                <h2 class="contact-info-title"><?php _e('How Can We Help?', 'haupt-recruitment'); ?></h2>
                <p class="contact-info-text"><?php _e('Whether you\'re a candidate looking for your next opportunity or an employer seeking top talent, our specialist consultants are ready to assist you.', 'haupt-recruitment'); ?></p>
                
                <div class="contact-methods">
                    <?php if ($has_offices) : ?>
                        <?php foreach ($offices as $office) : ?>
                        <div class="contact-method">
                            <div class="contact-method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="contact-method-content">
                                <h4><?php echo esc_html($office['name'] ?? __('Office', 'haupt-recruitment')); ?></h4>
                                
                                <?php if (!empty($office['address'])) : ?>
                                <address><?php echo nl2br(esc_html($office['address'])); ?></address>
                                <?php endif; ?>
                                
                                <?php if (!empty($office['phone'])) : ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $office['phone'])); ?>"><?php echo esc_html($office['phone']); ?></a>
                                <?php endif; ?>
                                
                                <?php if (!empty($office['email'])) : ?>
                                <a href="mailto:<?php echo esc_attr($office['email']); ?>"><?php echo esc_html($office['email']); ?></a>
                                <?php endif; ?>
                                
                                <?php if (!empty($office['hours'])) : ?>
                                <span><?php echo esc_html($office['hours']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="contact-method">
                            <div class="contact-method-content">
                                <p><?php _e('Contact information not available. Please use the form to get in touch.', 'haupt-recruitment'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Social Links -->
                <div class="contact-social">
                    <h4><?php _e('Follow Us', 'haupt-recruitment'); ?></h4>
                    <div class="social-links">
                        <?php $linkedin = haupt_get_social('linkedin'); if ($linkedin) : ?>
                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php $twitter = haupt_get_social('twitter'); if ($twitter) : ?>
                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php $facebook = haupt_get_social('facebook'); if ($facebook) : ?>
                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php $instagram = haupt_get_social('instagram'); if ($instagram) : ?>
                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-wrapper" data-aos="fade-left">
                <?php if ($is_job_enquiry) : ?>
                <div class="job-enquiry-notice" style="background: linear-gradient(135deg, var(--accent-500), var(--accent-600)); color: var(--white); padding: var(--space-4); border-radius: var(--radius-lg); margin-bottom: var(--space-6);">
                    <h4 style="color: var(--white); margin-bottom: var(--space-2);"><?php _e('Job Enquiry', 'haupt-recruitment'); ?></h4>
                    <p style="color: var(--white); margin: 0;"><?php printf(__('You are enquiring about: %s', 'haupt-recruitment'), '<strong>' . esc_html($job_title) . '</strong>'); ?></p>
                </div>
                <?php endif; ?>
                
                <form class="contact-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" data-validate>
                    <input type="hidden" name="action" value="haupt_contact_form">
                    <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
                    
                    <?php if ($is_job_enquiry) : ?>
                    <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
                    <input type="hidden" name="job_title" value="<?php echo esc_attr($job_title); ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name" class="form-label form-label-required"><?php _e('Full Name', 'haupt-recruitment'); ?></label>
                            <input type="text" id="contact-name" name="name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-email" class="form-label form-label-required"><?php _e('Email Address', 'haupt-recruitment'); ?></label>
                            <input type="email" id="contact-email" name="email" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-phone" class="form-label"><?php _e('Phone Number', 'haupt-recruitment'); ?></label>
                            <input type="tel" id="contact-phone" name="phone" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-enquiry" class="form-label form-label-required"><?php _e('Enquiry Type', 'haupt-recruitment'); ?></label>
                            <select id="contact-enquiry" name="enquiry_type" class="form-select" required>
                                <option value=""><?php _e('Select an option', 'haupt-recruitment'); ?></option>
                                <option value="candidate" <?php selected($is_job_enquiry); ?>><?php _e('I\'m looking for work', 'haupt-recruitment'); ?></option>
                                <option value="employer"><?php _e('I\'m looking to hire', 'haupt-recruitment'); ?></option>
                                <option value="general"><?php _e('General enquiry', 'haupt-recruitment'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-message" class="form-label form-label-required"><?php _e('Your Message', 'haupt-recruitment'); ?></label>
                        <textarea id="contact-message" name="message" class="form-textarea" rows="6" required placeholder="<?php _e('Tell us how we can help you...', 'haupt-recruitment'); ?>"><?php if ($is_job_enquiry) { echo esc_textarea(sprintf(__('I am interested in the %s position. Please could you provide more information about this role.', 'haupt-recruitment'), $job_title)); } ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="privacy" required>
                            <span><?php printf(__('I agree to the %sPrivacy Policy%s and consent to being contacted regarding my enquiry.', 'haupt-recruitment'), '<a href="' . esc_url(get_permalink(get_page_by_path('privacy-policy'))) . '">', '</a>'); ?></span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <?php _e('Send Message', 'haupt-recruitment'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<?php if ($map_embed) : ?>
<section class="section section-gray" id="location">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-label"><?php _e('Our Location', 'haupt-recruitment'); ?></span>
            <h2 class="section-title"><?php _e('Find Us', 'haupt-recruitment'); ?></h2>
        </div>
        
        <div class="contact-map" data-aos="fade-up">
            <?php echo $map_embed; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
get_footer();
