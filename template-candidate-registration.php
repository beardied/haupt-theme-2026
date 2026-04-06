<?php
/**
 * Template Name: Candidate Registration
 * Description: Complete registration form for candidates with PDF agreement generation
 *
 * @package Haupt_Recruitment_2026
 */

get_header();

// Check for success message
$registration_success = isset($_GET['registration']) && $_GET['registration'] === 'success';
$registered_name = isset($_GET['name']) ? urldecode($_GET['name']) : '';
?>

<header class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-overlay"></div>
    <div class="page-header-content">
        <span class="page-header-label"><?php _e('Join Us', 'haupt-recruitment'); ?></span>
        <h1 class="page-header-title"><?php _e('Candidate Registration', 'haupt-recruitment'); ?></h1>
        <p class="page-header-description"><?php _e('Register with Haupt Recruitment to access exclusive opportunities in the UK power and energy sector.', 'haupt-recruitment'); ?></p>
    </div>
</header>

<?php echo haupt_get_breadcrumbs(); ?>

<section class="section">
    <div class="container">
        <div class="expert-layout">
            <div class="expert-main">
                
                <?php if ($registration_success): ?>
                    <div class="success-message" data-aos="fade-up">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <h2><?php _e('Registration Successful!', 'haupt-recruitment'); ?></h2>
                        <p><?php printf(__('Thank you %s for registering with Haupt Recruitment. A consultant will be in touch shortly. Please check your email for a copy of your registration agreement.', 'haupt-recruitment'), esc_html($registered_name)); ?></p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Return to Homepage', 'haupt-recruitment'); ?></a>
                    </div>
                <?php else: ?>
                    
                    <div class="registration-form-container" data-aos="fade-up">
                        <form method="post" action="" enctype="multipart/form-data" class="registration-form" id="candidate-registration-form">
                            <?php wp_nonce_field('candidate_registration', 'haupt_candidate_registration'); ?>
                            <input type="hidden" name="_wp_http_referer" value="<?php echo esc_url(get_permalink()); ?>">
                            
                            <!-- Section 1: Personal Details -->
                            <div class="form-section">
                                <h3><?php _e('1. Personal Details', 'haupt-recruitment'); ?></h3>
                                
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
                                        <label for="phone"><?php _e('Phone Number *', 'haupt-recruitment'); ?></label>
                                        <input type="tel" name="phone" id="phone" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><?php _e('Date of Birth *', 'haupt-recruitment'); ?></label>
                                        <div class="dob-fields">
                                            <select name="dob_day" required>
                                                <option value="">Day</option>
                                                <?php for ($i = 1; $i <= 31; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <select name="dob_month" required>
                                                <option value="">Month</option>
                                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <select name="dob_year" required>
                                                <option value="">Year</option>
                                                <?php for ($i = date('Y') - 16; $i >= date('Y') - 70; $i--): ?>
                                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="ni_number"><?php _e('National Insurance Number *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ni_number" id="ni_number" placeholder="AB123456C" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 2: Address -->
                            <div class="form-section">
                                <h3><?php _e('2. Address', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="address_line1"><?php _e('Address Line 1 *', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="address_line1" id="address_line1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address_line2"><?php _e('Address Line 2', 'haupt-recruitment'); ?></label>
                                    <input type="text" name="address_line2" id="address_line2">
                                </div>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="town"><?php _e('Town/City *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="town" id="town" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="postcode"><?php _e('Postcode *', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="postcode" id="postcode" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 3: Emergency Contact -->
                            <div class="form-section">
                                <h3><?php _e('3. Emergency Contact', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="emergency_name"><?php _e('Emergency Contact Name', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="emergency_name" id="emergency_name">
                                    </div>
                                    <div class="form-group">
                                        <label for="emergency_phone"><?php _e('Emergency Contact Phone', 'haupt-recruitment'); ?></label>
                                        <input type="tel" name="emergency_phone" id="emergency_phone">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 4: Employment References -->
                            <div class="form-section">
                                <h3><?php _e('4. Employment References', 'haupt-recruitment'); ?></h3>
                                
                                <h4><?php _e('Most Recent Employer', 'haupt-recruitment'); ?></h4>
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="ref1_company"><?php _e('Company Name', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ref1_company" id="ref1_company">
                                    </div>
                                    <div class="form-group">
                                        <label for="ref1_contact"><?php _e('Contact Person', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ref1_contact" id="ref1_contact">
                                    </div>
                                </div>
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="ref1_phone"><?php _e('Phone Number', 'haupt-recruitment'); ?></label>
                                        <input type="tel" name="ref1_phone" id="ref1_phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="ref1_can_contact"><?php _e('Can we contact them?', 'haupt-recruitment'); ?></label>
                                        <select name="ref1_can_contact" id="ref1_can_contact">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <h4><?php _e('Previous Employer', 'haupt-recruitment'); ?></h4>
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="ref2_company"><?php _e('Company Name', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ref2_company" id="ref2_company">
                                    </div>
                                    <div class="form-group">
                                        <label for="ref2_contact"><?php _e('Contact Person', 'haupt-recruitment'); ?></label>
                                        <input type="text" name="ref2_contact" id="ref2_contact">
                                    </div>
                                </div>
                                <div class="form-row two-col">
                                    <div class="form-group">
                                        <label for="ref2_phone"><?php _e('Phone Number', 'haupt-recruitment'); ?></label>
                                        <input type="tel" name="ref2_phone" id="ref2_phone">
                                    </div>
                                    <div class="form-group">
                                        <label for="ref2_can_contact"><?php _e('Can we contact them?', 'haupt-recruitment'); ?></label>
                                        <select name="ref2_can_contact" id="ref2_can_contact">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section 5: Legal Declarations -->
                            <div class="form-section">
                                <h3><?php _e('5. Legal Declarations', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label><?php _e('Do you have any unspent criminal convictions? *', 'haupt-recruitment'); ?></label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="criminal_convictions" value="No" checked required> No</label>
                                        <label><input type="radio" name="criminal_convictions" value="Yes" required> Yes</label>
                                    </div>
                                    <textarea name="criminal_details" placeholder="If yes, please provide details"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php _e('Do you have permission to work in the UK? *', 'haupt-recruitment'); ?></label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="right_to_work" value="Yes - British Citizen" checked required> Yes - British Citizen</label>
                                        <label><input type="radio" name="right_to_work" value="Yes - EU Settled Status" required> Yes - EU Settled Status</label>
                                        <label><input type="radio" name="right_to_work" value="Yes - Visa" required> Yes - Visa</label>
                                        <label><input type="radio" name="right_to_work" value="No" required> No</label>
                                    </div>
                                    <textarea name="visa_details" placeholder="If you have a visa, please provide details"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php _e('Do you have any health issues or disability? *', 'haupt-recruitment'); ?></label>
                                    <div class="radio-group">
                                        <label><input type="radio" name="health_issues" value="No" checked required> No</label>
                                        <label><input type="radio" name="health_issues" value="Yes" required> Yes</label>
                                    </div>
                                    <textarea name="health_details" placeholder="If yes, please provide details"></textarea>
                                    <textarea name="adjustments" placeholder="What reasonable adjustments do you need? (if applicable)"></textarea>
                                </div>
                            </div>
                            
                            <!-- Section 6: Qualifications -->
                            <div class="form-section">
                                <h3><?php _e('6. Qualifications', 'haupt-recruitment'); ?></h3>
                                <div class="form-group">
                                    <label for="qualifications"><?php _e('Please list your relevant qualifications, certifications and authorisations:', 'haupt-recruitment'); ?></label>
                                    <textarea name="qualifications" id="qualifications" rows="5"></textarea>
                                </div>
                            </div>
                            
                            <!-- Section 7: Document Upload -->
                            <div class="form-section">
                                <h3><?php _e('7. Document Upload', 'haupt-recruitment'); ?></h3>
                                
                                <div class="form-group">
                                    <label for="cv_upload"><?php _e('Upload Your CV *', 'haupt-recruitment'); ?></label>
                                    <input type="file" name="cv_upload" id="cv_upload" accept=".pdf,.doc,.docx,.rtf,.txt" required>
                                    <p class="form-help"><?php _e('Accepted formats: PDF, DOC, DOCX, RTF, TXT (Max 5MB)', 'haupt-recruitment'); ?></p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="supporting_docs"><?php _e('Supporting Documents', 'haupt-recruitment'); ?></label>
                                    <input type="file" name="supporting_docs[]" id="supporting_docs" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <p class="form-help"><?php _e('Upload any additional certificates, authorisations, or supporting documents', 'haupt-recruitment'); ?></p>
                                </div>
                            </div>
                            
                            <!-- Section 8: Legal Agreements -->
                            <div class="form-section legal-section">
                                <h3><?php _e('8. Legal Agreements', 'haupt-recruitment'); ?></h3>
                                
                                <div class="legal-text">
                                    <h4><?php _e('Equal Opportunities', 'haupt-recruitment'); ?></h4>
                                    <p>Haupt Recruitment UK Ltd is committed to a policy of equal opportunities for all work seekers and shall adhere to such a policy at all times and will review on an on-going basis on all aspects of recruitment to avoid unlawful or undesirable discrimination. We will treat everyone equally irrespective of sex, sexual orientation, marital status, age, disability, race, colour, ethnic or national origin, religion, political beliefs or membership or non-membership of a Trade Union.</p>
                                    
                                    <h4><?php _e('Data Protection', 'haupt-recruitment'); ?></h4>
                                    <p>The information that you provide will be used by Haupt Recruitment UK Ltd to provide you work finding services. In providing this service to you, you consent to your personal data being included on a computerised database and consent to us transferring your personal details to our clients. We may check the information collected, with third parties or with other information held by us.</p>
                                    
                                    <h4><?php _e('Candidate Declaration', 'haupt-recruitment'); ?></h4>
                                    <p>I hereby confirm that the information given is true and correct. I consent to my personal data and CV being forwarded to clients. I consent to references being passed onto potential employers. If, during the course of a temporary assignment, the Client wishes to employ me direct, I acknowledge that Haupt Recruitment UK Ltd will be entitled either to charge the client an introduction/transfer fee, or to agree an extension of the hiring period with the Client.</p>
                                </div>
                                
                                <div class="form-group checkbox-group">
                                    <label>
                                        <input type="checkbox" name="agree_terms" required>
                                        <?php _e('I agree to the above terms and conditions *', 'haupt-recruitment'); ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <?php _e('Submit Registration', 'haupt-recruitment'); ?>
                                </button>
                            </div>
                            
                        </form>
                    </div>
                    
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <aside class="expert-sidebar">
                <div class="sidebar-widget">
                    <h4><?php _e('Why Register?', 'haupt-recruitment'); ?></h4>
                    <ul class="benefits-list">
                        <li>Access to exclusive opportunities</li>
                        <li>Dedicated consultant support</li>
                        <li>Fast-track application process</li>
                        <li>Industry-leading rates</li>
                        <li>Long-term contract opportunities</li>
                    </ul>
                </div>
                
                <div class="sidebar-widget sidebar-widget-highlight">
                    <h4><?php _e('48-Hour Opt-Out', 'haupt-recruitment'); ?></h4>
                    <p><?php _e('Already registered? If you wish to opt out of the Conduct of Employment Agencies Regulations 2003, you can complete the opt-out form.', 'haupt-recruitment'); ?></p>
                    <a href="<?php echo esc_url(home_url('/48-hour-opt-out/')); ?>" class="btn btn-primary btn-block">
                        <?php _e('Complete Opt-Out Form', 'haupt-recruitment'); ?>
                    </a>
                </div>
                
                <div class="sidebar-widget">
                    <h4><?php _e('Need Help?', 'haupt-recruitment'); ?></h4>
                    <p><?php _e('If you have any questions about the registration process, please contact us.', 'haupt-recruitment'); ?></p>
                    <?php $phone = haupt_get_option('phone_number'); if ($phone): ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="sidebar-phone"><?php echo esc_html($phone); ?></a>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
