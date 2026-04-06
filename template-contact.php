<?php
/**
 * Template Name: Contact Page
 * Description: Contact page with form and company details
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Get company info from options
$phone = haupt_get_phone();
$email = haupt_get_email();
$address = haupt_get_address();
$map_embed = '';
$opening_hours = ''; // Add to theme options if needed

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
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/contact-header.jpg" alt="" aria-hidden="true">
    </div>
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
                    <?php if ($phone) : ?>
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h4><?php _e('Phone', 'haupt-recruitment'); ?></h4>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                            <span><?php _e('Mon-Fri, 8:30am - 5:30pm', 'haupt-recruitment'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($email) : ?>
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h4><?php _e('Email', 'haupt-recruitment'); ?></h4>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                            <span><?php _e('We\'ll respond within 24 hours', 'haupt-recruitment'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($address) : ?>
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div class="contact-method-content">
                            <h4><?php _e('Address', 'haupt-recruitment'); ?></h4>
                            <address><?php echo wp_kses_post($address); ?></address>
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
