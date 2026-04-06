<?php
/**
 * Taxonomy Images for Role Expertise Categories
 * Adds image upload functionality to the sector categories
 *
 * @package Haupt_Recruitment_2026
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add image field to category add form
 */
add_action('role_expertise_category_add_form_fields', function() {
    wp_nonce_field('haupt_category_image', 'haupt_category_image_nonce');
    ?>
    <div class="form-field">
        <label><?php _e('Sector Image', 'haupt-recruitment'); ?></label>
        <input type="hidden" name="haupt_category_image_id" id="haupt_category_image_id" value="">
        <div id="haupt_category_image_preview" style="margin-bottom: 10px;"></div>
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
 * Add image field to category edit form
 */
add_action('role_expertise_category_edit_form_fields', function($term) {
    wp_nonce_field('haupt_category_image', 'haupt_category_image_nonce');
    
    $image_id = get_term_meta($term->term_id, 'haupt_category_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="haupt_category_image_id"><?php _e('Sector Image', 'haupt-recruitment'); ?></label>
        </th>
        <td>
            <input type="hidden" name="haupt_category_image_id" id="haupt_category_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="haupt_category_image_preview" style="margin-bottom: 10px;">
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
    $screen = get_current_screen();
    if ($screen && $screen->taxonomy === 'role_expertise_category') {
        wp_enqueue_media();
        wp_enqueue_script(
            'haupt-taxonomy-image',
            HAUPT_URI . '/assets/js/taxonomy-image.js',
            ['jquery'],
            HAUPT_VERSION,
            true
        );
    }
});

/**
 * Save category image
 */
add_action('created_role_expertise_category', 'haupt_save_category_image');
add_action('edited_role_expertise_category', 'haupt_save_category_image');

function haupt_save_category_image($term_id) {
    if (!isset($_POST['haupt_category_image_nonce']) || !wp_verify_nonce($_POST['haupt_category_image_nonce'], 'haupt_category_image')) {
        return;
    }
    
    if (isset($_POST['haupt_category_image_id'])) {
        $image_id = intval($_POST['haupt_category_image_id']);
        if ($image_id > 0) {
            update_term_meta($term_id, 'haupt_category_image', $image_id);
        } else {
            delete_term_meta($term_id, 'haupt_category_image');
        }
    }
}

/**
 * Add image column to category list
 */
add_filter('manage_edit-role_expertise_category_columns', function($columns) {
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
 * Display image in category list
 */
add_filter('manage_role_expertise_category_custom_column', function($content, $column, $term_id) {
    if ($column === 'image') {
        $image_id = get_term_meta($term_id, 'haupt_category_image', true);
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
 * Helper function to get category image
 */
function haupt_get_category_image($term_id, $size = 'medium') {
    $image_id = get_term_meta($term_id, 'haupt_category_image', true);
    if ($image_id) {
        return wp_get_attachment_image_url($image_id, $size);
    }
    return '';
}

/**
 * Helper function to get category image ID
 */
function haupt_get_category_image_id($term_id) {
    return get_term_meta($term_id, 'haupt_category_image', true);
}
