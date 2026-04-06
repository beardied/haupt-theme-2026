<?php
/**
 * Haupt Recruitment Forms Handler
 * Handles candidate registration, employer contact, and 48-hour opt-out forms
 *
 * @package Haupt_Recruitment_2026
 */

class Haupt_Forms {
    
    private static $instance = null;
    private $upload_dir;
    private $upload_url;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $upload_dir = wp_upload_dir();
        $this->upload_dir = $upload_dir['basedir'] . '/haupt-forms/';
        $this->upload_url = $upload_dir['baseurl'] . '/haupt-forms/';
        
        // Create upload directory if it doesn't exist
        if (!file_exists($this->upload_dir)) {
            wp_mkdir_p($this->upload_dir);
            // Protect directory with .htaccess
            file_put_contents($this->upload_dir . '.htaccess', "Options -Indexes\ndeny from all\n");
        }
        
        $this->init();
    }
    
    private function init() {
        // Form submission handlers
        add_action('init', [$this, 'handle_candidate_registration']);
        add_action('init', [$this, 'handle_employer_contact']);
        add_action('init', [$this, 'handle_opt_out_form']);
        
        // Admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Register settings
        add_action('admin_init', [$this, 'register_settings']);
        
        // Secure file download handler
        add_action('admin_init', [$this, 'handle_file_download']);
    }
    
    /**
     * Handle secure file download
     */
    public function handle_file_download() {
        if (!is_admin() || !current_user_can('manage_options')) {
            return;
        }
        
        if (!isset($_GET['page']) || $_GET['page'] !== 'haupt-form-files' || !isset($_GET['download'])) {
            return;
        }
        
        $filename = sanitize_file_name($_GET['download']);
        $filepath = $this->upload_dir . $filename;
        
        if (!file_exists($filepath) || !is_readable($filepath)) {
            wp_die(__('File not found.', 'haupt-recruitment'));
        }
        
        // Verify file is within upload directory (prevent directory traversal)
        $real_filepath = realpath($filepath);
        $real_upload_dir = realpath($this->upload_dir);
        if (strpos($real_filepath, $real_upload_dir) !== 0) {
            wp_die(__('Invalid file path.', 'haupt-recruitment'));
        }
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $content_types = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'rtf' => 'application/rtf',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        
        $content_type = isset($content_types[$ext]) ? $content_types[$ext] : 'application/octet-stream';
        
        nocache_headers();
        header('Content-Type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('X-Robots-Tag: noindex, nofollow', true);
        
        readfile($filepath);
        exit;
    }
    
    /**
     * Get form settings
     */
    public function get_settings() {
        return [
            'candidate_email' => get_option('haupt_form_candidate_email', get_option('admin_email')),
            'employer_email' => get_option('haupt_form_employer_email', get_option('admin_email')),
            'optout_email' => get_option('haupt_form_optout_email', get_option('admin_email')),
            'notification_from' => get_option('haupt_form_from_email', 'noreply@' . parse_url(home_url(), PHP_URL_HOST)),
        ];
    }
    
    /**
     * Handle Candidate Registration Form
     */
    public function handle_candidate_registration() {
        if (!isset($_POST['haupt_candidate_registration']) || !wp_verify_nonce($_POST['haupt_candidate_registration'], 'candidate_registration')) {
            return;
        }
        
        $settings = $this->get_settings();
        $errors = [];
        
        // Validation
        $required = ['first_name', 'last_name', 'email', 'phone', 'dob_day', 'dob_month', 'dob_year', 'ni_number', 'address_line1', 'postcode'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = sprintf(__('%s is required', 'haupt-recruitment'), ucfirst(str_replace('_', ' ', $field)));
            }
        }
        
        // Email validation
        if (!empty($_POST['email']) && !is_email($_POST['email'])) {
            $errors[] = __('Please enter a valid email address', 'haupt-recruitment');
        }
        
        // File upload validation
        if (empty($_FILES['cv_upload']['name'])) {
            $errors[] = __('Please upload your CV', 'haupt-recruitment');
        }
        
        if (!empty($errors)) {
            wp_die(implode('<br>', $errors) . '<br><a href="' . esc_url($_SERVER['HTTP_REFERER']) . '">' . __('Go Back', 'haupt-recruitment') . '</a>');
        }
        
        // Process file upload
        $uploaded_files = [];
        if (!empty($_FILES['cv_upload']['name'])) {
            $cv_result = $this->upload_file($_FILES['cv_upload'], 'cv');
            if (is_wp_error($cv_result)) {
                wp_die($cv_result->get_error_message() . '<br><a href="' . esc_url($_SERVER['HTTP_REFERER']) . '">' . __('Go Back', 'haupt-recruitment') . '</a>');
            }
            $uploaded_files['cv'] = $cv_result;
        }
        
        // Process supporting documents
        if (!empty($_FILES['supporting_docs']['name'][0])) {
            foreach ($_FILES['supporting_docs']['name'] as $key => $name) {
                if (!empty($name)) {
                    $file = [
                        'name' => $_FILES['supporting_docs']['name'][$key],
                        'type' => $_FILES['supporting_docs']['type'][$key],
                        'tmp_name' => $_FILES['supporting_docs']['tmp_name'][$key],
                        'error' => $_FILES['supporting_docs']['error'][$key],
                        'size' => $_FILES['supporting_docs']['size'][$key],
                    ];
                    $doc_result = $this->upload_file($file, 'docs');
                    if (!is_wp_error($doc_result)) {
                        $uploaded_files['supporting'][] = $doc_result;
                    }
                }
            }
        }
        
        // Generate PDF Agreement
        $pdf_path = $this->generate_registration_pdf($_POST, $uploaded_files);
        
        // Send emails
        $this->send_candidate_emails($_POST, $uploaded_files, $pdf_path, $settings);
        
        // Redirect to success page
        wp_redirect(add_query_arg([
            'registration' => 'success',
            'name' => urlencode(sanitize_text_field($_POST['first_name'] . ' ' . $_POST['last_name']))
        ], $_POST['_wp_http_referer']));
        exit;
    }
    
    /**
     * Handle Employer Contact Form
     */
    public function handle_employer_contact() {
        if (!isset($_POST['haupt_employer_contact']) || !wp_verify_nonce($_POST['haupt_employer_contact'], 'employer_contact')) {
            return;
        }
        
        $settings = $this->get_settings();
        $errors = [];
        
        // Validation
        $required = ['company_name', 'contact_name', 'email', 'phone', 'industry'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = sprintf(__('%s is required', 'haupt-recruitment'), ucfirst(str_replace('_', ' ', $field)));
            }
        }
        
        if (!empty($errors)) {
            wp_die(implode('<br>', $errors) . '<br><a href="' . esc_url($_SERVER['HTTP_REFERER']) . '">' . __('Go Back', 'haupt-recruitment') . '</a>');
        }
        
        // Send email to admin with HTML
        $to = $settings['employer_email'];
        $subject = sprintf(__('New Employer Enquiry: %s', 'haupt-recruitment'), sanitize_text_field($_POST['company_name']));
        
        $admin_content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">New Employer Enquiry</h2>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">A new employer enquiry has been submitted via the website.</p>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 8px; margin: 20px 0;">
                <tr><td style="padding: 20px;">
                    <table width="100%" cellpadding="8" cellspacing="0" border="0">
                        <tr>
                            <td width="140" style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Company:</td>
                            <td style="color: #002d72; font-weight: 500; border-bottom: 1px solid #e5e5e5;">' . esc_html($_POST['company_name']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Contact Name:</td>
                            <td style="color: #444; border-bottom: 1px solid #e5e5e5;">' . esc_html($_POST['contact_name']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Email:</td>
                            <td style="border-bottom: 1px solid #e5e5e5;"><a href="mailto:' . esc_attr($_POST['email']) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($_POST['email']) . '</a></td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Phone:</td>
                            <td style="border-bottom: 1px solid #e5e5e5;"><a href="tel:' . esc_attr(preg_replace('/\s+/', '', $_POST['phone'])) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($_POST['phone']) . '</a></td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Industry:</td>
                            <td style="color: #444; border-bottom: 1px solid #e5e5e5;">' . esc_html($_POST['industry']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Work Location:</td>
                            <td style="color: #444; border-bottom: 1px solid #e5e5e5;">' . esc_html($_POST['work_location']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Workers Required:</td>
                            <td style="color: #444; border-bottom: 1px solid #e5e5e5;">' . esc_html($_POST['num_workers']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600;">Start Date:</td>
                            <td style="color: #444;">' . esc_html($_POST['start_date']) . '</td>
                        </tr>
                    </table>
                </td></tr>
            </table>
            
            <div style="background-color: #e8f4f5; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h4 style="color: #002d72; margin: 0 0 10px 0; font-size: 15px;">Job Requirements</h4>
                <p style="color: #444; margin: 0; line-height: 1.6; white-space: pre-wrap;">' . nl2br(esc_html($_POST['job_requirements'])) . '</p>
            </div>
        ';
        
        $message = $this->get_email_template($admin_content, 'New Employer Enquiry');
        
        $headers = [
            'From: ' . $settings['notification_from'],
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        wp_mail($to, $subject, $message, $headers);
        
        // Send auto-response
        $this->send_employer_auto_response($_POST, $settings);
        
        // Redirect to success page
        wp_redirect(add_query_arg('enquiry', 'success', $_POST['_wp_http_referer']));
        exit;
    }
    
    /**
     * Handle 48-Hour Opt-Out Form
     */
    public function handle_opt_out_form() {
        if (!isset($_POST['haupt_opt_out']) || !wp_verify_nonce($_POST['haupt_opt_out'], 'opt_out_form')) {
            return;
        }
        
        $settings = $this->get_settings();
        $errors = [];
        
        $required = ['first_name', 'last_name', 'email', 'ni_number', 'signature'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = sprintf(__('%s is required', 'haupt-recruitment'), ucfirst(str_replace('_', ' ', $field)));
            }
        }
        
        if (!empty($errors)) {
            wp_die(implode('<br>', $errors) . '<br><a href="' . esc_url($_SERVER['HTTP_REFERER']) . '">' . __('Go Back', 'haupt-recruitment') . '</a>');
        }
        
        // Generate PDF
        $pdf_path = $this->generate_optout_pdf($_POST);
        
        // Send emails
        $this->send_optout_emails($_POST, $pdf_path, $settings);
        
        wp_redirect(add_query_arg('optout', 'success', $_POST['_wp_http_referer']));
        exit;
    }
    
    /**
     * Upload file securely
     */
    private function upload_file($file, $type = 'cv') {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        $allowed_types = [
            'cv' => ['pdf', 'doc', 'docx', 'rtf', 'txt'],
            'docs' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']
        ];
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_types[$type])) {
            return new WP_Error('invalid_type', __('Invalid file type. Allowed: ', 'haupt-recruitment') . implode(', ', $allowed_types[$type]));
        }
        
        // Generate unique filename
        $filename = sanitize_file_name($file['name']);
        $unique_name = wp_unique_filename($this->upload_dir, $filename);
        $filepath = $this->upload_dir . $unique_name;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return new WP_Error('upload_failed', __('File upload failed', 'haupt-recruitment'));
        }
        
        chmod($filepath, 0644);
        
        return [
            'path' => $filepath,
            'url' => $this->upload_url . $unique_name,
            'filename' => $unique_name,
            'original_name' => $file['name']
        ];
    }
    
    /**
     * Generate Registration PDF using TCPDF
     */
    private function generate_registration_pdf($data, $files) {
        require_once get_template_directory() . '/lib/tcpdf-src/tcpdf.php';
        
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document info
        $pdf->SetCreator('Haupt Recruitment');
        $pdf->SetAuthor('Haupt Recruitment UK Ltd');
        $pdf->SetTitle('Registration Agreement');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetFooterMargin(10);
        
        // Add page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'HAUPT RECRUITMENT UK LTD', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, 'Candidate Registration Agreement', 0, 1, 'C');
        $pdf->Ln(5);
        
        // Personal Details Section
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '1. PERSONAL DETAILS', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->Cell(40, 7, 'First Name:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['first_name']), 0, 1);
        
        $pdf->Cell(40, 7, 'Last Name:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['last_name']), 0, 1);
        
        $pdf->Cell(40, 7, 'Email:', 0, 0);
        $pdf->Cell(0, 7, sanitize_email($data['email']), 0, 1);
        
        $pdf->Cell(40, 7, 'Phone:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['phone']), 0, 1);
        
        $pdf->Cell(40, 7, 'Date of Birth:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['dob_day'] . '/' . $data['dob_month'] . '/' . $data['dob_year']), 0, 1);
        
        $pdf->Cell(40, 7, 'NI Number:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ni_number']), 0, 1);
        
        $pdf->Ln(3);
        
        // Address
        $pdf->Cell(40, 7, 'Address:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['address_line1']), 0, 1);
        if (!empty($data['address_line2'])) {
            $pdf->Cell(40, 7, '', 0, 0);
            $pdf->Cell(0, 7, sanitize_text_field($data['address_line2']), 0, 1);
        }
        $pdf->Cell(40, 7, 'Town/City:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['town']), 0, 1);
        $pdf->Cell(40, 7, 'Postcode:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['postcode']), 0, 1);
        
        $pdf->Ln(5);
        
        // Emergency Contact
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '2. EMERGENCY CONTACT', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->Cell(50, 7, 'Emergency Contact Name:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['emergency_name']), 0, 1);
        
        $pdf->Cell(50, 7, 'Emergency Contact Phone:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['emergency_phone']), 0, 1);
        
        $pdf->Ln(5);
        
        // References
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '3. EMPLOYMENT REFERENCES', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        
        // Reference 1
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Most Recent Employer:', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 7, 'Company:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref1_company']), 0, 1);
        $pdf->Cell(40, 7, 'Contact:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref1_contact']), 0, 1);
        $pdf->Cell(40, 7, 'Phone:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref1_phone']), 0, 1);
        $pdf->Cell(40, 7, 'Can Contact:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref1_can_contact']), 0, 1);
        
        $pdf->Ln(3);
        
        // Reference 2
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Previous Employer:', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 7, 'Company:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref2_company']), 0, 1);
        $pdf->Cell(40, 7, 'Contact:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref2_contact']), 0, 1);
        $pdf->Cell(40, 7, 'Phone:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref2_phone']), 0, 1);
        $pdf->Cell(40, 7, 'Can Contact:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ref2_can_contact']), 0, 1);
        
        $pdf->Ln(5);
        
        // Criminal Convictions
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '4. CRIMINAL CONVICTIONS', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(70, 7, 'Any unspent criminal convictions?', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['criminal_convictions']), 0, 1);
        if (!empty($data['criminal_details'])) {
            $pdf->MultiCell(0, 6, 'Details: ' . sanitize_textarea_field($data['criminal_details']), 0, 'L');
        }
        
        $pdf->Ln(3);
        
        // Right to Work
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '5. RIGHT TO WORK IN UK', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(70, 7, 'Permission to work in UK?', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['right_to_work']), 0, 1);
        if (!empty($data['visa_details'])) {
            $pdf->MultiCell(0, 6, 'Visa Details: ' . sanitize_textarea_field($data['visa_details']), 0, 'L');
        }
        
        $pdf->Ln(3);
        
        // Health and Disability
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '6. HEALTH AND DISABILITY', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(90, 7, 'Any health issues or disability?', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['health_issues']), 0, 1);
        if (!empty($data['health_details'])) {
            $pdf->MultiCell(0, 6, 'Details: ' . sanitize_textarea_field($data['health_details']), 0, 'L');
        }
        if (!empty($data['adjustments'])) {
            $pdf->MultiCell(0, 6, 'Reasonable Adjustments: ' . sanitize_textarea_field($data['adjustments']), 0, 'L');
        }
        
        $pdf->Ln(3);
        
        // Qualifications
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '7. QUALIFICATIONS', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 6, sanitize_textarea_field($data['qualifications']), 0, 'L');
        
        // New page for legal text
        $pdf->AddPage();
        
        // Equal Opportunities
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '8. EQUAL OPPORTUNITIES', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $equal_opp_text = "Haupt Recruitment UK Ltd is committed to a policy of equal opportunities for all work seekers and shall adhere to such a policy at all times and will review on an on-going basis on all aspects of recruitment to avoid unlawful or undesirable discrimination. We will treat everyone equally irrespective of sex, sexual orientation, marital status, age, disability, race, colour, ethnic or national origin, religion, political beliefs or membership or non-membership of a Trade Union.";
        $pdf->MultiCell(0, 5, $equal_opp_text, 0, 'L');
        
        $pdf->Ln(3);
        
        // Data Protection
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '9. DATA PROTECTION', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $data_text = "The information that you provide will be used by Haupt Recruitment UK Ltd to provide you work finding services. In providing this service to you, you consent to your personal data being included on a computerised database and consent to us transferring your personal details to our clients. We may check the information collected, with third parties or with other information held by us.";
        $pdf->MultiCell(0, 5, $data_text, 0, 'L');
        
        $pdf->Ln(3);
        
        // Candidate Declaration
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, '10. CANDIDATE DECLARATION', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $declare_text = "I hereby confirm that the information given is true and correct. I consent to my personal data and CV being forwarded to clients. I consent to references being passed onto potential employers. If, during the course of a temporary assignment, the Client wishes to employ me direct, I acknowledge that Haupt Recruitment UK Ltd will be entitled either to charge the client an introduction/transfer fee, or to agree an extension of the hiring period with the Client.";
        $pdf->MultiCell(0, 5, $declare_text, 0, 'L');
        
        $pdf->Ln(5);
        
        // Signature
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'Digitally Signed By:', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, sanitize_text_field($data['first_name'] . ' ' . $data['last_name']), 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'Date:', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, date('d/m/Y'), 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'IP Address:', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, $_SERVER['REMOTE_ADDR'], 0, 1);
        
        // Save PDF
        $filename = sanitize_file_name($data['last_name'] . '-' . $data['first_name'] . '-' . date('Ymd-His')) . '-Registration.pdf';
        $filepath = $this->upload_dir . $filename;
        $pdf->Output($filepath, 'F');
        
        return $filepath;
    }
    
    /**
     * Generate 48-Hour Opt-Out PDF
     */
    private function generate_optout_pdf($data) {
        require_once get_template_directory() . '/lib/tcpdf-src/tcpdf.php';
        
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $pdf->SetCreator('Haupt Recruitment');
        $pdf->SetAuthor('Haupt Recruitment UK Ltd');
        $pdf->SetTitle('48 Hour Opt-Out Agreement');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, '48 HOUR OPT-OUT AGREEMENT', 0, 1, 'C');
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', '', 10);
        $text = "Under the Conduct of Employment Agencies and Employment Businesses Regulations 2003, there are certain restrictions on the ability of an agency to introduce a work seeker to a hirer where that work seeker is already known to the hirer. These restrictions are subject to a number of exceptions, including where the work seeker has agreed in writing that the Regulations shall not apply.\n\n";
        $text .= "By signing this agreement, you are confirming that Haupt Recruitment UK Ltd may introduce you to a hirer even if you are already known to that hirer, without the restrictions of the Regulations applying.\n\n";
        $pdf->MultiCell(0, 6, $text, 0, 'L');
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'PERSONAL DETAILS', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->Cell(40, 7, 'Full Name:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['first_name'] . ' ' . $data['last_name']), 0, 1);
        
        $pdf->Cell(40, 7, 'Email:', 0, 0);
        $pdf->Cell(0, 7, sanitize_email($data['email']), 0, 1);
        
        $pdf->Cell(40, 7, 'NI Number:', 0, 0);
        $pdf->Cell(0, 7, sanitize_text_field($data['ni_number']), 0, 1);
        
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 8, 'DECLARATION', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        
        $declare = "I hereby agree that the Conduct of Employment Agencies and Employment Businesses Regulations 2003 shall not apply in relation to any introduction by Haupt Recruitment UK Ltd to a hirer, notwithstanding that I may already be known to that hirer.\n\n";
        $declare .= "I understand that I have the right to cancel this opt-out agreement at any time by giving written notice to Haupt Recruitment UK Ltd.";
        $pdf->MultiCell(0, 6, $declare, 0, 'L');
        
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'Digitally Signed:', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, sanitize_text_field($data['signature']), 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'Date:', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 7, date('d/m/Y'), 0, 1);
        
        $filename = sanitize_file_name($data['last_name'] . '-' . $data['first_name'] . '-' . date('Ymd-His')) . '-OptOut.pdf';
        $filepath = $this->upload_dir . $filename;
        $pdf->Output($filepath, 'F');
        
        return $filepath;
    }
    
    /**
     * Get HTML email template wrapper
     */
    private function get_email_template($content, $title = '') {
        $site_name = get_bloginfo('name');
        $site_url = home_url();
        $primary_color = '#002d72';
        $accent_color = '#00a5b5';
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f5f7fa;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, {$primary_color} 0%, #001a4d 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">HAUPT RECRUITMENT</h1>
                            <p style="color: #00a5b5; margin: 8px 0 0 0; font-size: 13px; text-transform: uppercase; letter-spacing: 2px;">UK Power & Energy Specialists</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            {$content}
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px 40px; text-align: center; border-top: 1px solid #e5e5e5;">
                            <p style="color: #666; margin: 0 0 15px 0; font-size: 14px; line-height: 1.6;">
                                <strong style="color: {$primary_color};">Haupt Recruitment UK Ltd</strong><br>
                                Specialist recruitment for the UK Power, Wind, Offshore, HV & Cable sectors
                            </p>
                            <p style="margin: 15px 0 0 0;">
                                <a href="{$site_url}" style="color: {$accent_color}; text-decoration: none; font-weight: 600;">www.hauptrecruitment.co.uk</a>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- Legal Footer -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin-top: 20px;">
                    <tr>
                        <td style="text-align: center; padding: 0 20px;">
                            <p style="color: #999; font-size: 12px; line-height: 1.5; margin: 0;">
                                This email was sent by Haupt Recruitment UK Ltd. If you have any questions, please contact us.<br>
                                &copy; {$site_name} - All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
    
    /**
     * Send candidate registration emails
     */
    private function send_candidate_emails($data, $files, $pdf_path, $settings) {
        $to = $settings['candidate_email'];
        $subject = sprintf(__('New Candidate Registration: %s %s', 'haupt-recruitment'), $data['first_name'], $data['last_name']);
        
        // HTML email for admin
        $admin_content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">New Candidate Registration</h2>
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">A new candidate has registered on the website. Please find their details below:</p>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 8px; margin: 20px 0;">
                <tr><td style="padding: 20px;">
                    <table width="100%" cellpadding="8" cellspacing="0" border="0">
                        <tr>
                            <td width="120" style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Name:</td>
                            <td style="color: #002d72; font-weight: 500; border-bottom: 1px solid #e5e5e5;">' . esc_html($data['first_name'] . ' ' . $data['last_name']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Email:</td>
                            <td style="border-bottom: 1px solid #e5e5e5;"><a href="mailto:' . esc_attr($data['email']) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($data['email']) . '</a></td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Phone:</td>
                            <td style="border-bottom: 1px solid #e5e5e5;"><a href="tel:' . esc_attr(preg_replace('/\s+/', '', $data['phone'])) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($data['phone']) . '</a></td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">NI Number:</td>
                            <td style="color: #444; border-bottom: 1px solid #e5e5e5;">' . esc_html($data['ni_number']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600;">Location:</td>
                            <td style="color: #444;">' . esc_html($data['town'] . ', ' . $data['postcode']) . '</td>
                        </tr>
                    </table>
                </td></tr>
            </table>
            
            <p style="color: #666; font-size: 14px; margin: 20px 0 0 0;">The registration agreement PDF is attached to this email.</p>
        ';
        
        $admin_message = $this->get_email_template($admin_content, 'New Candidate Registration');
        $headers = [
            'From: ' . $settings['notification_from'],
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        $attachments = [$pdf_path];
        if (isset($files['cv'])) {
            $attachments[] = $files['cv']['path'];
        }
        
        wp_mail($to, $subject, $admin_message, $headers, $attachments);
        
        // Auto-response to candidate with HTML
        $candidate_subject = __('Your Registration with Haupt Recruitment', 'haupt-recruitment');
        
        $phone = haupt_get_option('phone_number');
        $email = haupt_get_option('email_address');
        
        $candidate_content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">Thank You for Registering, ' . esc_html($data['first_name']) . '!</h2>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">We have successfully received your registration details and CV. One of our specialist consultants will review your information and contact you shortly.</p>
            
            <div style="background: linear-gradient(135deg, #e8f4f5 0%, #d4ebf0 100%); border-left: 4px solid #00a5b5; padding: 20px; border-radius: 0 8px 8px 0; margin: 25px 0;">
                <h3 style="color: #002d72; margin: 0 0 10px 0; font-size: 16px;">What Happens Next?</h3>
                <ul style="color: #444; margin: 0; padding-left: 20px; line-height: 1.8;">
                    <li>Our team will review your CV and experience</li>
                    <li>We will match you with suitable opportunities</li>
                    <li>A consultant will contact you to discuss your career goals</li>
                </ul>
            </div>
            
            <p style="color: #444; font-size: 15px; line-height: 1.6; margin: 20px 0;">A copy of your registration agreement is attached to this email for your records.</p>
            
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="color: #002d72; margin: 0 0 15px 0; font-size: 15px;">Contact Us</h4>
                <p style="color: #666; margin: 5px 0; font-size: 14px;">
                    <strong>Phone:</strong> <a href="tel:' . esc_attr(preg_replace('/\s+/', '', $phone)) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($phone) . '</a><br>
                    <strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($email) . '</a>
                </p>
            </div>
            
            <p style="color: #444; font-size: 15px; line-height: 1.6; margin: 20px 0 0 0;">We look forward to helping you find your next opportunity in the power and energy sector.</p>
        ';
        
        $candidate_message = $this->get_email_template($candidate_content, 'Registration Confirmation');
        
        wp_mail($data['email'], $candidate_subject, $candidate_message, $headers, [$pdf_path]);
    }
    
    /**
     * Send employer auto-response
     */
    private function send_employer_auto_response($data, $settings) {
        $subject = __('Your enquiry with Haupt Recruitment', 'haupt-recruitment');
        
        $content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">Thank You for Your Enquiry</h2>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Dear ' . esc_html($data['contact_name']) . ',</p>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Thank you for contacting Haupt Recruitment UK Ltd regarding staffing requirements for <strong style="color: #002d72;">' . esc_html($data['company_name']) . '</strong>.</p>
            
            <div style="background: linear-gradient(135deg, #e8f4f5 0%, #d4ebf0 100%); border-left: 4px solid #00a5b5; padding: 20px; border-radius: 0 8px 8px 0; margin: 25px 0;">
                <h3 style="color: #002d72; margin: 0 0 10px 0; font-size: 16px;">What Happens Next?</h3>
                <ul style="color: #444; margin: 0; padding-left: 20px; line-height: 1.8;">
                    <li>One of our specialist consultants will review your requirements</li>
                    <li>We will contact you within 24 hours to discuss your needs</li>
                    <li>We will provide tailored staffing solutions for your project</li>
                </ul>
            </div>
            
            <p style="color: #444; font-size: 15px; line-height: 1.6; margin: 20px 0;">In the meantime, if you have any urgent requirements, please do not hesitate to call us directly.</p>
            
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="color: #002d72; margin: 0 0 15px 0; font-size: 15px;">Your Enquiry Summary</h4>
                <table width="100%" cellpadding="5" cellspacing="0" border="0" style="font-size: 14px;">
                    <tr>
                        <td width="100" style="color: #666;">Company:</td>
                        <td style="color: #002d72; font-weight: 500;">' . esc_html($data['company_name']) . '</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Industry:</td>
                        <td style="color: #444;">' . esc_html($data['industry']) . '</td>
                    </tr>
                </table>
            </div>
        ';
        
        $message = $this->get_email_template($content, 'Enquiry Confirmation');
        
        $headers = [
            'From: ' . $settings['notification_from'],
            'Content-Type: text/html; charset=UTF-8'
        ];
        wp_mail($data['email'], $subject, $message, $headers);
    }
    
    /**
     * Send opt-out emails
     */
    private function send_optout_emails($data, $pdf_path, $settings) {
        $to = $settings['optout_email'];
        $subject = sprintf(__('48-Hour Opt-Out Agreement: %s %s', 'haupt-recruitment'), $data['first_name'], $data['last_name']);
        
        // Admin notification with HTML
        $admin_content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">48-Hour Opt-Out Agreement Submitted</h2>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">A new 48-hour opt-out agreement has been submitted via the website.</p>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 8px; margin: 20px 0;">
                <tr><td style="padding: 20px;">
                    <table width="100%" cellpadding="8" cellspacing="0" border="0">
                        <tr>
                            <td width="120" style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Name:</td>
                            <td style="color: #002d72; font-weight: 500; border-bottom: 1px solid #e5e5e5;">' . esc_html($data['first_name'] . ' ' . $data['last_name']) . '</td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600; border-bottom: 1px solid #e5e5e5;">Email:</td>
                            <td style="border-bottom: 1px solid #e5e5e5;"><a href="mailto:' . esc_attr($data['email']) . '" style="color: #00a5b5; text-decoration: none;">' . esc_html($data['email']) . '</a></td>
                        </tr>
                        <tr>
                            <td style="color: #666; font-weight: 600;">NI Number:</td>
                            <td style="color: #444;">' . esc_html($data['ni_number']) . '</td>
                        </tr>
                    </table>
                </td></tr>
            </table>
            
            <p style="color: #666; font-size: 14px; margin: 20px 0 0 0;">The signed opt-out agreement PDF is attached to this email.</p>
        ';
        
        $admin_message = $this->get_email_template($admin_content, '48-Hour Opt-Out Agreement');
        $headers = [
            'From: ' . $settings['notification_from'],
            'Content-Type: text/html; charset=UTF-8'
        ];
        wp_mail($to, $subject, $admin_message, $headers, [$pdf_path]);
        
        // Candidate confirmation with HTML
        $candidate_subject = __('Your 48-Hour Opt-Out Agreement', 'haupt-recruitment');
        
        $candidate_content = '
            <h2 style="color: #002d72; margin: 0 0 20px 0; font-size: 22px;">48-Hour Opt-Out Agreement Confirmation</h2>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Dear ' . esc_html($data['first_name']) . ',</p>
            
            <p style="color: #444; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Thank you for submitting your 48-hour opt-out agreement with Haupt Recruitment UK Ltd.</p>
            
            <div style="background: linear-gradient(135deg, #e8f4f5 0%, #d4ebf0 100%); border-left: 4px solid #00a5b5; padding: 20px; border-radius: 0 8px 8px 0; margin: 25px 0;">
                <h3 style="color: #002d72; margin: 0 0 10px 0; font-size: 16px;">What This Means</h3>
                <p style="color: #444; margin: 0; line-height: 1.6;">By opting out, you agree that the Conduct of Employment Agencies and Employment Businesses Regulations 2003 shall not apply to introductions made by Haupt Recruitment, even if you are already known to the hirer.</p>
            </div>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; border-radius: 0 8px 8px 0; margin: 25px 0;">
                <h3 style="color: #856404; margin: 0 0 10px 0; font-size: 15px;">Important Information</h3>
                <p style="color: #856404; margin: 0; line-height: 1.6; font-size: 14px;">You have the right to cancel this opt-out agreement at any time by giving written notice to Haupt Recruitment UK Ltd. Please contact us if you wish to cancel.</p>
            </div>
            
            <p style="color: #444; font-size: 15px; line-height: 1.6; margin: 20px 0;">A copy of your signed agreement is attached to this email for your records.</p>
        ';
        
        $candidate_message = $this->get_email_template($candidate_content, '48-Hour Opt-Out Confirmation');
        
        wp_mail($data['email'], $candidate_subject, $candidate_message, $headers, [$pdf_path]);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Form Settings', 'haupt-recruitment'),
            __('Form Settings', 'haupt-recruitment'),
            'manage_options',
            'haupt-forms',
            [$this, 'render_settings_page'],
            'dashicons-feedback',
            30
        );
        
        add_submenu_page(
            'haupt-forms',
            __('Email Settings', 'haupt-recruitment'),
            __('Email Settings', 'haupt-recruitment'),
            'manage_options',
            'haupt-forms',
            [$this, 'render_settings_page']
        );
        
        add_submenu_page(
            'haupt-forms',
            __('Submitted Files', 'haupt-recruitment'),
            __('Submitted Files', 'haupt-recruitment'),
            'manage_options',
            'haupt-form-files',
            [$this, 'render_files_page']
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('haupt_form_settings', 'haupt_form_candidate_email');
        register_setting('haupt_form_settings', 'haupt_form_employer_email');
        register_setting('haupt_form_settings', 'haupt_form_optout_email');
        register_setting('haupt_form_settings', 'haupt_form_from_email');
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        $settings = $this->get_settings();
        ?>
        <div class="wrap">
            <h1><?php _e('Form Email Settings', 'haupt-recruitment'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('haupt_form_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Candidate Registration Email', 'haupt-recruitment'); ?></th>
                        <td>
                            <input type="email" name="haupt_form_candidate_email" value="<?php echo esc_attr($settings['candidate_email']); ?>" class="regular-text">
                            <p class="description"><?php _e('Where candidate registration notifications are sent', 'haupt-recruitment'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Employer Enquiry Email', 'haupt-recruitment'); ?></th>
                        <td>
                            <input type="email" name="haupt_form_employer_email" value="<?php echo esc_attr($settings['employer_email']); ?>" class="regular-text">
                            <p class="description"><?php _e('Where employer enquiries are sent', 'haupt-recruitment'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Opt-Out Agreement Email', 'haupt-recruitment'); ?></th>
                        <td>
                            <input type="email" name="haupt_form_optout_email" value="<?php echo esc_attr($settings['optout_email']); ?>" class="regular-text">
                            <p class="description"><?php _e('Where 48-hour opt-out agreements are sent', 'haupt-recruitment'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('From Email Address', 'haupt-recruitment'); ?></th>
                        <td>
                            <input type="email" name="haupt_form_from_email" value="<?php echo esc_attr($settings['notification_from']); ?>" class="regular-text">
                            <p class="description"><?php _e('The "from" address for all form notifications', 'haupt-recruitment'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Render files page
     */
    public function render_files_page() {
        $files = $this->get_uploaded_files();
        ?>
        <div class="wrap">
            <h1><?php _e('Submitted Files', 'haupt-recruitment'); ?></h1>
            
            <h2><?php _e('Registration Agreements', 'haupt-recruitment'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Filename', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Date', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Size', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Actions', 'haupt-recruitment'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($files['pdfs'])): ?>
                        <tr><td colspan="4"><?php _e('No files found', 'haupt-recruitment'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach ($files['pdfs'] as $file): ?>
                            <tr>
                                <td><?php echo esc_html($file['name']); ?></td>
                                <td><?php echo esc_html($file['date']); ?></td>
                                <td><?php echo esc_html(size_format($file['size'])); ?></td>
                                <td>
                                    <a href="<?php echo esc_url($file['download_url']); ?>" class="button" target="_blank">
                                        <?php _e('Download', 'haupt-recruitment'); ?>
                                    </a>
                                    <a href="mailto:?subject=Registration Agreement&body=Please find the attached registration agreement." class="button">
                                        <?php _e('Email', 'haupt-recruitment'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <h2 style="margin-top: 30px;"><?php _e('CV Uploads', 'haupt-recruitment'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Filename', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Date', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Size', 'haupt-recruitment'); ?></th>
                        <th><?php _e('Actions', 'haupt-recruitment'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($files['cvs'])): ?>
                        <tr><td colspan="4"><?php _e('No files found', 'haupt-recruitment'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach ($files['cvs'] as $file): ?>
                            <tr>
                                <td><?php echo esc_html($file['name']); ?></td>
                                <td><?php echo esc_html($file['date']); ?></td>
                                <td><?php echo esc_html(size_format($file['size'])); ?></td>
                                <td>
                                    <a href="<?php echo esc_url($file['download_url']); ?>" class="button" target="_blank">
                                        <?php _e('Download', 'haupt-recruitment'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Get uploaded files list
     */
    private function get_uploaded_files() {
        $files = ['pdfs' => [], 'cvs' => [], 'docs' => []];
        
        if (!is_dir($this->upload_dir)) {
            return $files;
        }
        
        $iterator = new DirectoryIterator($this->upload_dir);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && !$fileinfo->isDot()) {
                $filename = $fileinfo->getFilename();
                $ext = strtolower($fileinfo->getExtension());
                
                $file_data = [
                    'name' => $filename,
                    'path' => $fileinfo->getPathname(),
                    'url' => $this->upload_url . $filename,
                    'download_url' => admin_url('admin.php?page=haupt-form-files&download=' . urlencode($filename)),
                    'size' => $fileinfo->getSize(),
                    'date' => date('Y-m-d H:i:s', $fileinfo->getMTime())
                ];
                
                if ($ext === 'pdf') {
                    $files['pdfs'][] = $file_data;
                } elseif (in_array($ext, ['doc', 'docx', 'rtf', 'txt'])) {
                    $files['cvs'][] = $file_data;
                } else {
                    $files['docs'][] = $file_data;
                }
            }
        }
        
        // Sort by date descending
        foreach ($files as $key => $list) {
            usort($files[$key], function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        return $files;
    }
}

// Initialize
Haupt_Forms::get_instance();
