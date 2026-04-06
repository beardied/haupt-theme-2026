<?php
/**
 * Template Name: 48 Hour Opt-Out
 * Description: Form for candidates to opt out of Conduct of Employment Agencies Regulations 2003
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

$optout_success = isset($_GET['optout']) && $_GET['optout'] === 'success';
?>

<header class="page-header">
    <div class="page-header-bg">
        <img src="<?php echo HAUPT_URI; ?>/assets/images/page-header-bg.jpg" alt="" aria-hidden="true">
    </div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Legal Notice', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('48-Hour Opt-Out Agreement', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Complete this form to opt out of the restrictions under the Conduct of Employment Agencies and Employment Businesses Regulations 2003.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<?php echo haupt_get_breadcrumbs(); ?>

<section class="section">
    <div class="container">
        
        <?php if ($optout_success): ?>
            <div class="success-message" data-aos="fade-up" style="max-width: 800px; margin: 0 auto;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <h2><?php _e('Opt-Out Agreement Submitted', 'haupt-recruitment'); ?></h2>
                <p><?php _e('Your 48-hour opt-out agreement has been submitted successfully. A copy of the signed agreement has been emailed to you for your records.', 'haupt-recruitment'); ?></p>
                <p><?php _e('You have the right to cancel this opt-out agreement at any time by giving written notice to Haupt Recruitment UK Ltd.', 'haupt-recruitment'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Return to Homepage', 'haupt-recruitment'); ?></a>
            </div>
        <?php else: ?>
            
            <div class="expert-layout">
                <div class="expert-main">
                    <div class="registration-form-container" data-aos="fade-up">
                        
                        <div class="legal-notice-box">
                            <h3><?php _e('Important Information', 'haupt-recruitment'); ?></h3>
                            <p>Under the <strong>Conduct of Employment Agencies and Employment Businesses Regulations 2003</strong>, there are restrictions on an agency's ability to introduce a work seeker to a hirer where that work seeker is already known to the hirer.</p>
                            <p>By signing this opt-out agreement, you are confirming that Haupt Recruitment UK Ltd may introduce you to a hirer even if you are already known to that hirer, without the restrictions of the Regulations applying.</p>
                            <p><strong>You have the right to cancel this opt-out agreement at any time by giving written notice to Haupt Recruitment UK Ltd.</strong></p>
                        </div>
                        
                        <form method="post" action="" class="registration-form" id="optout-form">
                            <?php wp_nonce_field('opt_out_form', 'haupt_opt_out'); ?>
                            <input type="hidden" name="_wp_http_referer" value="<?php echo esc_url(get_permalink()); ?>">
                            
                            <div class="form-section">
                                <h3><?php _e('Your Details', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="first_name"><?php _e('First Name *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="first_name" id="first_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name"><?php _e('Last Name *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="last_name" id="last_name" required>
                                    </div>
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="email"><?php _e('Email Address *', 'haupt-recruitment'); ?></label>
                                        <input type="email" name="email" id="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ni_number"><?php _e('National Insurance Number *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ni_number" id="ni_number" placeholder="AB123456C" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section legal-section">
                                <h3><?php _e('Declaration', 'haupt-recruitment'); ?></h3>
                                
                                <div class="legal-text">
                                    <p>I hereby agree that the <strong>Conduct of Employment Agencies and Employment Businesses Regulations 2003</strong> shall not apply in relation to any introduction by Haupt Recruitment UK Ltd to a hirer, notwithstanding that I may already be known to that hirer.</p>
                                    
                                    <p>I understand that I have the right to cancel this opt-out agreement at any time by giving written notice to Haupt Recruitment UK Ltd.</p>
                                    
                                    <p>By entering my full name below, I am digitally signing this agreement and confirming that I understand and agree to the terms stated above.</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="signature"><?php _e('Digital Signature (Enter your full name) *', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="signature" id="signature" required placeholder="Enter your full name as your digital signature">
                                </div>
                                
                                <div class="form-group checkbox-group">
                                    <label>
                                        <input type="checkbox" name="confirm_understanding" required>
                                        <?php _e('I confirm that I have read and understood this opt-out agreement and agree to its terms *', 'haupt-recruitment'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <?php _e('Submit Opt-Out Agreement', 'haupt-recruitment'); ?>
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <aside class="expert-sidebar">
                    <div class="sidebar-widget">
                        <h4><?php _e('What is the 48-Hour Rule?', 'haupt-recruitment'); ?></h4>
                        <p><?php _e('The Conduct of Employment Agencies Regulations 2003 restrict agencies from introducing candidates to employers where the candidate is already known to that employer.', 'haupt-recruitment'); ?></p>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h4><?php _e('Why Opt Out?', 'haupt-recruitment'); ?></h4>
                        <p><?php _e('Opting out allows Haupt Recruitment to introduce you to employers even if you have previously worked with them or applied directly, increasing your opportunities.', 'haupt-recruitment'); ?></p>
                    </div>
                    
                    <div class="sidebar-widget sidebar-widget-highlight">
                        <h4><?php _e('Your Rights', 'haupt-recruitment'); ?></h4>
                        <ul class="benefits-list">
                            <li>You can cancel at any time</li>
                            <li>Written notice required</li>
                            <li>No penalty for cancellation</li>
                            <li>Agreement is voluntary</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h4><?php _e('Need Help?', 'haupt-recruitment'); ?></h4>
                        <p><?php _e('If you have questions about this opt-out agreement, please contact us.', 'haupt-recruitment'); ?></p>
                        <?php $phone = haupt_get_option('phone_number'); if ($phone): ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="sidebar-phone"><?php echo esc_html($phone); ?></a>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
            
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
