<?php
/**
 * Template Name: Employer Contact
 * Description: Contact form for employers looking to hire through Haupt Recruitment
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

$enquiry_success = isset($_GET['enquiry']) && $_GET['enquiry'] === 'success';
?>

<header class="page-header">
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/page-header-bg.jpg" alt="" aria-hidden="true">
    </div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('For Employers', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('Hire Through Haupt', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Connect with skilled professionals across the UK power and energy sector. Tell us about your requirements and we\'ll find the right candidates for you.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<?php echo haupt_get_breadcrumbs(); ?>

<section class="section">
    <div class="container">
        <div class="expert-layout">
            <div class="expert-main">
                
                <?php if ($enquiry_success): ?>
                    <div class="success-message" data-aos="fade-up">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <h2><?php _e('Enquiry Submitted!', 'haupt-recruitment'); ?></h2>
                        <p><?php _e('Thank you for your enquiry. One of our consultants will be in touch with you shortly to discuss your requirements.', 'haupt-recruitment'); ?></p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Return to Homepage', 'haupt-recruitment'); ?></a>
                    </div>
                <?php else: ?>
                    
                    <div class="registration-form-container" data-aos="fade-up">
                        <form method="post" action="" class="registration-form" id="employer-contact-form">
                            <?php wp_nonce_field('employer_contact', 'haupt_employer_contact'); ?>
                            <input type="hidden" name="_wp_http_referer" value="<?php echo esc_url(get_permalink()); ?>">
                            
                            <!-- Company Details -->
                            <div class="form-section">
                                <h3><?php _e('Company Information', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="company_name"><?php _e('Company Name *', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="company_name" id="company_name" required>
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="industry"><?php _e('Industry/Sector *', 'haupt-recruitment'); ?></label>
                                        <select name="industry" id="industry" required>
                                            <option value="">Select Industry</option>
                                            <option value="Power Infrastructure">Power Infrastructure</option>
                                            <option value="Wind Energy">Wind Energy</option>
                                            <option value="Offshore">Offshore</option>
                                            <option value="HV & Cable">HV & Cable</option>
                                            <option value="Renewables">Renewables</option>
                                            <option value="Utilities">Utilities</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="company_size"><?php _e('Company Size', 'haupt-recruitment'); ?></label>
                                        <select name="company_size" id="company_size">
                                            <option value="">Select Size</option>
                                            <option value="1-50">1-50 employees</option>
                                            <option value="51-200">51-200 employees</option>
                                            <option value="201-1000">201-1000 employees</option>
                                            <option value="1000+">1000+ employees</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Details -->
                            <div class="form-section">
                                <h3><?php _e('Contact Information', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="contact_name"><?php _e('Contact Name *', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="contact_name" id="contact_name" required>
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="email"><?php _e('Email Address *', 'haupt-recruitment'); ?></label>
                                        <input type="email" name="email" id="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone"><?php _e('Phone Number *', 'haupt-recruitment'); ?></label>
                                        <input type="tel" name="phone" id="phone" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="job_title"><?php _e('Your Job Title', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="job_title" id="job_title">
                                </div>
                            </div>
                            
                            <!-- Job Requirements -->
                            <div class="form-section">
                                <h3><?php _e('Job Requirements', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="job_title_required"><?php _e('Job Title(s) Required', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="job_title_required" id="job_title_required" placeholder="e.g. Electrical Engineer, Project Manager">
                                </div>
                                
                                <div class="form-group">
                                    <label for="job_requirements"><?php _e('Detailed Requirements *', 'haupt-recruitment'); ?></label>
                                    <textarea name="job_requirements" id="job_requirements" rows="5" required placeholder="Please describe the roles, skills required, experience level, qualifications needed, etc."></textarea>
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="work_location"><?php _e('Work Location(s)', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="work_location" id="work_location" placeholder="e.g. Nationwide, Scotland, London">
                                    </div>
                                    <div class="form-group">
                                        <label for="num_workers"><?php _e('Number of Workers Required', 'haupt-recruitment'); ?></label>
                                        <input type="number" name="num_workers" id="num_workers" min="1">
                                    </div>
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="contract_type"><?php _e('Contract Type', 'haupt-recruitment'); ?></label>
                                        <select name="contract_type" id="contract_type">
                                            <option value="">Select Type</option>
                                            <option value="Temporary">Temporary</option>
                                            <option value="Contract">Contract</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temp-to-Perm">Temp-to-Perm</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date"><?php _e('Expected Start Date', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="start_date" id="start_date" placeholder="ASAP or specific date">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="duration"><?php _e('Contract Duration', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="duration" id="duration" placeholder="e.g. 3 months, Ongoing, Permanent">
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="form-section">
                                <h3><?php _e('Additional Information', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="additional_info"><?php _e('Any Other Details', 'haupt-recruitment'); ?></label>
                                    <textarea name="additional_info" id="additional_info" rows="4" placeholder="Any additional information that would help us understand your requirements"></textarea>
                                </div>
                                
                                <div class="form-group checkbox-group">
                                    <label>
                                        <input type="checkbox" name="contact_consent" required>
                                        <?php _e('I consent to being contacted by Haupt Recruitment regarding this enquiry *', 'haupt-recruitment'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <?php _e('Submit Enquiry', 'haupt-recruitment'); ?>
                                </button>
                            </div>
                            
                        </form>
                    </div>
                    
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="expert-sidebar">
                <div class="sidebar-widget">
                    <h4><?php _e('Our Services', 'haupt-recruitment'); ?></h4>
                    <ul class="sidebar-links">
                        <li>Temporary Staffing</li>
                        <li>Contract Recruitment</li>
                        <li>Permanent Placement</li>
                        <li>Project Teams</li>
                        <li>Executive Search</li>
                    </ul>
                </div>
                
                <div class="sidebar-widget sidebar-widget-highlight">
                    <h4><?php _e('Why Haupt?', 'haupt-recruitment'); ?></h4>
                    <ul class="benefits-list">
                        <li>Industry specialists since 2008</li>
                        <li>Verified candidate database</li>
                        <li>Fast turnaround times</li>
                        <li>UK-wide coverage</li>
                        <li>Compliance assured</li>
                    </ul>
                </div>
                
                <div class="sidebar-widget">
                    <h4><?php _e('Contact Us Directly', 'haupt-recruitment'); ?></h4>
                    <?php $phone = haupt_get_option('phone_number'); if ($phone): ?>
                        <p><strong>Tel:</strong> <?php echo esc_html($phone); ?></p>
                    <?php endif; ?>
                    <?php $email = haupt_get_option('email_address'); if ($email): ?>
                        <p><strong>Email:</strong> <?php echo esc_html($email); ?></p>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
