<?php
/**
 * Taxonomy Images for Job Sectors
 * Adds image upload functionality to job_sector taxonomy
 *
 * @package Haupt_Recruitment_2026
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add image field to job_sector add form
 */
add_action('job_sector_add_form_fields', function() {
    wp_nonce_field('haupt_sector_image', 'haupt_sector_image_nonce');
    ?>
    <div class="form-field">
        <label><?php _e('Sector Image', 'haupt-recruitment'); ?></label>
        <input type="hidden" name="haupt_sector_image_id" id="haupt_sector_image_id" value="">
        <div id="haupt_sector_image_preview" style="margin-bottom: 10px;"></div>
        <button type="button" class="button" id="haupt_upload_image_button">
            <?php _e('Upload Image', 'haupt-recruitment'); ?>
        </button>
        <button type="button" class="button" id="haupt_remove_image_button" style="display: none;">
            <?php _e('Remove Image', 'haupt-recruitment'); ?>
        </button>
        <p class="description"><?php _e('Upload an image to represent this sector on the homepage.', 'haupt-recruitment'); ?></p>
    </div>
    <?php
});

/**
 * Add image field to job_sector edit form
 */
add_action('job_sector_edit_form_fields', function($term) {
    wp_nonce_field('haupt_sector_image', 'haupt_sector_image_nonce');
    
    $image_id = get_term_meta($term->term_id, 'haupt_sector_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="haupt_sector_image_id"><?php _e('Sector Image', 'haupt-recruitment'); ?></label>
        </th>
        <td>
            <input type="hidden" name="haupt_sector_image_id" id="haupt_sector_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="haupt_sector_image_preview" style="margin-bottom: 10px;">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; height: auto; border-radius: 4px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button" id="haupt_upload_image_button">
                <?php echo $image_url ? __('Change Image', 'haupt-recruitment') : __('Upload Image', 'haupt-recruitment'); ?>
            </button>
            <button type="button" class="button" id="haupt_remove_image_button" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>;">
                <?php _e('Remove Image', 'haupt-recruitment'); ?>
            </button>
            <p class="description"><?php _e('Upload an image to represent this sector on the homepage. Recommended size: 600x400 pixels.', 'haupt-recruitment'); ?></p>
        </td>
    </tr>
    <?php
});

/**
 * Enqueue media uploader scripts
 */
add_action('admin_enqueue_scripts', function($hook) {
    // Check if we're on the job_sector taxonomy pages
    if ($hook === 'edit-tags.php' || $hook === 'term.php') {
        $screen = get_current_screen();
        if ($screen && $screen->taxonomy === 'job_sector') {
            wp_enqueue_media();
            wp_enqueue_script(
                'haupt-taxonomy-image',
                HAUPT_URI . '/assets/js/taxonomy-image.js',
                ['jquery'],
                HAUPT_VERSION,
                true
            );
        }
    }
});

/**
 * Save sector image
 */
add_action('created_job_sector', 'haupt_save_sector_image');
add_action('edited_job_sector', 'haupt_save_sector_image');

function haupt_save_sector_image($term_id) {
    if (!isset($_POST['haupt_sector_image_nonce']) || !wp_verify_nonce($_POST['haupt_sector_image_nonce'], 'haupt_sector_image')) {
        return;
    }
    
    if (isset($_POST['haupt_sector_image_id'])) {
        $image_id = intval($_POST['haupt_sector_image_id']);
        if ($image_id > 0) {
            update_term_meta($term_id, 'haupt_sector_image', $image_id);
        } else {
            delete_term_meta($term_id, 'haupt_sector_image');
        }
    }
}

/**
 * Add image column to sector list
 */
add_filter('manage_edit-job_sector_columns', function($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'cb') {
            $new_columns['image'] = __('Image', 'haupt-recruitment');
        }
    }
    return $new_columns;
});

/**
 * Display image in sector list
 */
add_filter('manage_job_sector_custom_column', function($content, $column, $term_id) {
    if ($column === 'image') {
        $image_id = get_term_meta($term_id, 'haupt_sector_image', true);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            if ($image_url) {
                return '<img src="' . esc_url($image_url) . '" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">';
            }
        }
        return '<span style="color: #999;">—</span>';
    }
    return $content;
}, 10, 3);

/**
 * Helper function to get sector image
 */
function haupt_get_sector_image($term_id, $size = 'medium') {
    $image_id = get_term_meta($term_id, 'haupt_sector_image', true);
    if ($image_id) {
        return wp_get_attachment_image_url($image_id, $size);
    }
    return '';
}

/**
 * Helper function to get sector image ID
 */
function haupt_get_sector_image_id($term_id) {
    return get_term_meta($term_id, 'haupt_sector_image', true);
}
