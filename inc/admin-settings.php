<?php
/**
 * Haupt Recruitment Admin Settings Page
 * Manages multiple offices, contact details, and global theme settings
 *
 * @package Haupt_Recruitment_2026
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Haupt Recruitment Settings Page
 */
add_action('admin_menu', function() {
    add_menu_page(
        __('Haupt Settings', 'haupt-recruitment'),
        __('Haupt Settings', 'haupt-recruitment'),
        'manage_options',
        'haupt-settings',
        'haupt_render_settings_page',
        'dashicons-building',
        25
    );
});

/**
 * Register Settings
 */
add_action('admin_init', function() {
    // Company Info
    register_setting('haupt_settings_group', 'haupt_company_name');
    register_setting('haupt_settings_group', 'haupt_company_email');
    register_setting('haupt_settings_group', 'haupt_company_registration');
    
    // Social Media
    register_setting('haupt_settings_group', 'haupt_social_linkedin');
    register_setting('haupt_settings_group', 'haupt_social_twitter');
    register_setting('haupt_settings_group', 'haupt_social_facebook');
    register_setting('haupt_settings_group', 'haupt_social_instagram');
    
    // Offices (stored as JSON array)
    register_setting('haupt_settings_group', 'haupt_offices');
    
    // Homepage Stats
    register_setting('haupt_settings_group', 'haupt_stat_placements');
    register_setting('haupt_settings_group', 'haupt_stat_clients');
    register_setting('haupt_settings_group', 'haupt_stat_candidates');
    register_setting('haupt_settings_group', 'haupt_stat_years');
    register_setting('haupt_settings_group', 'haupt_stat_retention');
});

/**
 * Render Settings Page
 */
function haupt_render_settings_page() {
    $offices = haupt_get_offices();
    ?>
    <div class="wrap">
        <h1><?php _e('Haupt Recruitment Settings', 'haupt-recruitment'); ?></h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('haupt_settings_group'); ?>
            
            <!-- Company Information -->
            <h2 class="title"><?php _e('Company Information', 'haupt-recruitment'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="haupt_company_name"><?php _e('Company Name', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="text" id="haupt_company_name" name="haupt_company_name" 
                               value="<?php echo esc_attr(get_option('haupt_company_name', 'Haupt Recruitment UK Ltd')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_company_email"><?php _e('General Email', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="email" id="haupt_company_email" name="haupt_company_email" 
                               value="<?php echo esc_attr(get_option('haupt_company_email', '')); ?>" 
                               class="regular-text">
                        <p class="description"><?php _e('Main contact email shown across the site', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_company_registration"><?php _e('Company Registration Number', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="text" id="haupt_company_registration" name="haupt_company_registration" 
                               value="<?php echo esc_attr(get_option('haupt_company_registration', '')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
            
            <!-- Offices Section -->
            <h2 class="title"><?php _e('Offices & Locations', 'haupt-recruitment'); ?></h2>
            <p class="description"><?php _e('Add your office locations. These will appear in the footer and contact pages.', 'haupt-recruitment'); ?></p>
            
            <div id="haupt-offices-container">
                <?php foreach ($offices as $index => $office) : ?>
                <div class="haupt-office-box" data-index="<?php echo $index; ?>" style="background: #f9f9f9; border: 1px solid #ccd0d4; padding: 15px; margin: 10px 0; border-radius: 4px;">
                    <h3 style="margin-top: 0;"><?php printf(__('Office %d', 'haupt-recruitment'), $index + 1); ?> <button type="button" class="button button-link-delete haupt-remove-office" style="float: right;"><?php _e('Remove', 'haupt-recruitment'); ?></button></h3>
                    
                    <table class="form-table" style="margin-top: 0;">
                        <tr>
                            <th scope="row"><label><?php _e('Office Name', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <input type="text" name="haupt_offices[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($office['name'] ?? ''); ?>" 
                                       class="regular-text" placeholder="e.g., London Office">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e('Address', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <textarea name="haupt_offices[<?php echo $index; ?>][address]" rows="3" class="large-text" placeholder="Street address, City, Postcode"><?php echo esc_textarea($office['address'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e('Phone Number', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <input type="tel" name="haupt_offices[<?php echo $index; ?>][phone]" 
                                       value="<?php echo esc_attr($office['phone'] ?? ''); ?>" 
                                       class="regular-text" placeholder="e.g., 020 7123 4567">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e('Email', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <input type="email" name="haupt_offices[<?php echo $index; ?>][email]" 
                                       value="<?php echo esc_attr($office['email'] ?? ''); ?>" 
                                       class="regular-text" placeholder="e.g., london@hauptrecruitment.co.uk">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e('Opening Hours', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <input type="text" name="haupt_offices[<?php echo $index; ?>][hours]" 
                                       value="<?php echo esc_attr($office['hours'] ?? ''); ?>" 
                                       class="regular-text" placeholder="e.g., Mon-Fri 9am-5:30pm">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php _e('Map URL', 'haupt-recruitment'); ?></label></th>
                            <td>
                                <input type="url" name="haupt_offices[<?php echo $index; ?>][map_url]" 
                                       value="<?php echo esc_attr($office['map_url'] ?? ''); ?>" 
                                       class="regular-text" placeholder="Google Maps link">
                            </td>
                        </tr>
                    </table>
                </div>
                <?php endforeach; ?>
            </div>
            
            <p>
                <button type="button" class="button button-secondary" id="haupt-add-office">
                    <?php _e('+ Add New Office', 'haupt-recruitment'); ?>
                </button>
            </p>
            
            <!-- Social Media -->
            <h2 class="title"><?php _e('Social Media', 'haupt-recruitment'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="haupt_social_linkedin"><?php _e('LinkedIn', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="url" id="haupt_social_linkedin" name="haupt_social_linkedin" 
                               value="<?php echo esc_attr(get_option('haupt_social_linkedin', '')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_social_twitter"><?php _e('Twitter / X', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="url" id="haupt_social_twitter" name="haupt_social_twitter" 
                               value="<?php echo esc_attr(get_option('haupt_social_twitter', '')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_social_facebook"><?php _e('Facebook', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="url" id="haupt_social_facebook" name="haupt_social_facebook" 
                               value="<?php echo esc_attr(get_option('haupt_social_facebook', '')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_social_instagram"><?php _e('Instagram', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="url" id="haupt_social_instagram" name="haupt_social_instagram" 
                               value="<?php echo esc_attr(get_option('haupt_social_instagram', '')); ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
            
            <!-- Homepage Stats -->
            <h2 class="title"><?php _e('Homepage Statistics', 'haupt-recruitment'); ?></h2>
            <p class="description"><?php _e('These statistics appear in the hero section and counter section of the homepage.', 'haupt-recruitment'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="haupt_stat_placements"><?php _e('Placements Made', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="number" id="haupt_stat_placements" name="haupt_stat_placements" 
                               value="<?php echo esc_attr(get_option('haupt_stat_placements', '2500')); ?>" 
                               class="small-text">
                        <p class="description"><?php _e('Shown as: "Placements Made" (hero) and "Successful Placements" (counter)', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_stat_clients"><?php _e('Client Companies', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="number" id="haupt_stat_clients" name="haupt_stat_clients" 
                               value="<?php echo esc_attr(get_option('haupt_stat_clients', '150')); ?>" 
                               class="small-text">
                        <p class="description"><?php _e('Shown as: "Client Companies" (hero) and "Client Partners" (counter)', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_stat_candidates"><?php _e('Qualified Candidates', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="number" id="haupt_stat_candidates" name="haupt_stat_candidates" 
                               value="<?php echo esc_attr(get_option('haupt_stat_candidates', '15000')); ?>" 
                               class="small-text">
                        <p class="description"><?php _e('Shown as: "Candidates" (hero) and "Qualified Candidates" (counter)', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_stat_years"><?php _e('Years Experience', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="number" id="haupt_stat_years" name="haupt_stat_years" 
                               value="<?php echo esc_attr(get_option('haupt_stat_years', '15')); ?>" 
                               class="small-text">
                        <p class="description"><?php _e('Shown as: "Years Experience" in the hero section', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="haupt_stat_retention"><?php _e('Client Retention Rate (%)', 'haupt-recruitment'); ?></label></th>
                    <td>
                        <input type="number" id="haupt_stat_retention" name="haupt_stat_retention" 
                               value="<?php echo esc_attr(get_option('haupt_stat_retention', '98')); ?>" 
                               class="small-text" min="0" max="100">
                        <p class="description"><?php _e('Shown as: "Client Retention Rate" in the counter section (add % symbol)', 'haupt-recruitment'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Settings', 'haupt-recruitment')); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Add new office
        $('#haupt-add-office').on('click', function() {
            var container = $('#haupt-offices-container');
            var index = container.children('.haupt-office-box').length;
            
            var template = `
                <div class="haupt-office-box" data-index="${index}" style="background: #f9f9f9; border: 1px solid #ccd0d4; padding: 15px; margin: 10px 0; border-radius: 4px;">
                    <h3 style="margin-top: 0;">Office ${index + 1} <button type="button" class="button button-link-delete haupt-remove-office" style="float: right;">Remove</button></h3>
                    
                    <table class="form-table" style="margin-top: 0;">
                        <tr>
                            <th scope="row"><label>Office Name</label></th>
                            <td>
                                <input type="text" name="haupt_offices[${index}][name]" 
                                       value="" class="regular-text" placeholder="e.g., London Office">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Address</label></th>
                            <td>
                                <textarea name="haupt_offices[${index}][address]" rows="3" class="large-text" placeholder="Street address, City, Postcode"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Phone Number</label></th>
                            <td>
                                <input type="tel" name="haupt_offices[${index}][phone]" 
                                       value="" class="regular-text" placeholder="e.g., 020 7123 4567">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Email</label></th>
                            <td>
                                <input type="email" name="haupt_offices[${index}][email]" 
                                       value="" class="regular-text" placeholder="e.g., london@hauptrecruitment.co.uk">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Opening Hours</label></th>
                            <td>
                                <input type="text" name="haupt_offices[${index}][hours]" 
                                       value="" class="regular-text" placeholder="e.g., Mon-Fri 9am-5:30pm">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Map URL</label></th>
                            <td>
                                <input type="url" name="haupt_offices[${index}][map_url]" 
                                       value="" class="regular-text" placeholder="Google Maps link">
                            </td>
                        </tr>
                    </table>
                </div>
            `;
            
            container.append(template);
        });
        
        // Remove office
        $(document).on('click', '.haupt-remove-office', function() {
            if (confirm('Are you sure you want to remove this office?')) {
                $(this).closest('.haupt-office-box').remove();
                // Reindex remaining offices
                $('#haupt-offices-container .haupt-office-box').each(function(i) {
                    $(this).attr('data-index', i);
                    $(this).find('h3').html(`Office ${i + 1} <button type="button" class="button button-link-delete haupt-remove-office" style="float: right;">Remove</button>`);
                    $(this).find('input, textarea').each(function() {
                        var name = $(this).attr('name');
                        var newName = name.replace(/\[\d+\]/, '[' + i + ']');
                        $(this).attr('name', newName);
                    });
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * Helper function to get all offices
 */
function haupt_get_offices() {
    $offices = get_option('haupt_offices', []);
    
    // If empty, return sample/default structure
    if (empty($offices)) {
        return [];
    }
    
    // Ensure it's an array
    if (!is_array($offices)) {
        $offices = [];
    }
    
    // Reindex array
    return array_values($offices);
}

/**
 * Helper function to get primary office (first one)
 */
function haupt_get_primary_office() {
    $offices = haupt_get_offices();
    return !empty($offices) ? $offices[0] : null;
}

/**
 * Helper function to get company email
 */
function haupt_get_company_email() {
    return get_option('haupt_company_email', '');
}

/**
 * Helper function to get company name
 */
function haupt_get_company_name() {
    return get_option('haupt_company_name', 'Haupt Recruitment UK Ltd');
}

/**
 * Helper function to get social media URLs
 */
function haupt_get_social_url($platform) {
    $option_name = 'haupt_social_' . sanitize_key($platform);
    return get_option($option_name, '');
}
