<?php
/**
 * Theme Footer
 *
 * @package Haupt_Recruitment_2026
 */
?>
    </main>
    
    <!-- Footer CTA -->
    <div class="footer-cta">
        <div class="container">
            <h2 class="footer-cta-title">Ready to Power Your Career or Team?</h2>
            <p class="footer-cta-text">Whether you're seeking your next opportunity in the power sector or need skilled professionals for your project, we're here to connect talent with opportunity.</p>
            <div class="footer-cta-buttons">
                <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-lg" style="background: var(--white); color: var(--accent-600);">
                    Browse Jobs
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <a href="<?php echo esc_url(home_url('/employers/')); ?>" class="btn btn-ghost btn-lg">
                    Hire Talent
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Footer -->
    <footer class="site-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- Column 1: Brand -->
                    <div>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                            <span class="logo-main">HAUPT</span>
                            <span class="logo-sub">Recruitment</span>
                        </a>
                        <p class="footer-tagline">Specialist recruitment for the UK Power, Wind, Offshore, HV & Cable sectors.</p>
                        
                        <div class="footer-contact">
                            <?php $phone = haupt_get_phone(); if ($phone) : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="footer-contact-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <?php echo esc_html($phone); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php $email = haupt_get_email(); if ($email) : ?>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="footer-contact-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <?php echo esc_html($email); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php $address = haupt_get_address(); if ($address) : ?>
                            <div class="footer-contact-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <?php echo nl2br(esc_html($address)); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="footer-social">
                            <?php $linkedin = haupt_get_social('linkedin'); if ($linkedin) : ?>
                            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php $twitter = haupt_get_social('twitter'); if ($twitter) : ?>
                            <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php $facebook = haupt_get_social('facebook'); if ($facebook) : ?>
                            <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Column 2: For Candidates -->
                    <div>
                        <h4 class="footer-title">For Candidates</h4>
                        <ul class="footer-menu">
                            <li><a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>">Search Jobs</a></li>
                            <li><a href="<?php echo esc_url(home_url('/upload-cv/')); ?>">Upload CV</a></li>
                            <li><a href="<?php echo esc_url(get_post_type_archive_link('role_expertise')); ?>">Career Guides</a></li>
                        </ul>
                    </div>
                    
                    <!-- Column 3: For Employers -->
                    <div>
                        <h4 class="footer-title">For Employers</h4>
                        <ul class="footer-menu">
                            <li><a href="<?php echo esc_url(home_url('/employers/')); ?>">Our Services</a></li>
                            <li><a href="<?php echo esc_url(home_url('/submit-vacancy/')); ?>">Submit Vacancy</a></li>
                            <li><a href="<?php echo esc_url(home_url('/sectors/')); ?>">Sectors We Cover</a></li>
                        </ul>
                    </div>
                    
                    <!-- Column 4: Company -->
                    <div>
                        <h4 class="footer-title">Company</h4>
                        <ul class="footer-menu">
                            <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a></li>
                            <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">News & Insights</a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
                    </p>
                    <div class="footer-legal">
                        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
                        <a href="<?php echo esc_url(home_url('/terms-conditions/')); ?>">Terms & Conditions</a>
                        <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scroll to Top Button -->
    <button type="button" class="scroll-top" id="scroll-top" aria-label="<?php _e('Scroll to top', 'haupt-recruitment'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
    
</div><!-- /.site-wrapper -->

<?php wp_footer(); ?>

</body>
</html>
