<?php
/**
 * Schema Type Meta Box
 * Allows editors to override the default schema type for any page
 *
 * @package Haupt_Recruitment_2026
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Available schema types for pages
 */
function haupt_get_schema_type_options() {
    return [
        ''              => __('Auto-detect (default)', 'haupt-recruitment'),
        'WebPage'       => __('WebPage', 'haupt-recruitment'),
        'AboutPage'     => __('AboutPage', 'haupt-recruitment'),
        'ContactPage'   => __('ContactPage', 'haupt-recruitment'),
        'FAQPage'       => __('FAQPage', 'haupt-recruitment'),
        'ItemList'      => __('ItemList', 'haupt-recruitment'),
        'CollectionPage'=> __('CollectionPage', 'haupt-recruitment'),
        'Service'       => __('Service', 'haupt-recruitment'),
        'Product'       => __('Product', 'haupt-recruitment'),
        'ProfilePage'   => __('ProfilePage', 'haupt-recruitment'),
        'SearchResultsPage' => __('SearchResultsPage', 'haupt-recruitment'),
    ];
}

/**
 * Add schema type meta box to pages
 */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'haupt_schema_type',
        __('Schema.org Type', 'haupt-recruitment'),
        'haupt_render_schema_meta_box',
        'page',
        'side',
        'default'
    );
});

/**
 * Render the schema type meta box
 */
function haupt_render_schema_meta_box($post) {
    wp_nonce_field('haupt_schema_type_save', 'haupt_schema_type_nonce');
    
    $current_type = get_post_meta($post->ID, 'haupt_schema_type', true);
    $options = haupt_get_schema_type_options();
    ?>
    <p>
        <label for="haupt_schema_type">
            <?php _e('Select the structured data type for this page. Leave as "Auto-detect" to let the theme decide.', 'haupt-recruitment'); ?>
        </label>
    </p>
    <select name="haupt_schema_type" id="haupt_schema_type" style="width: 100%;">
        <?php foreach ($options as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($current_type, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="description">
        <?php _e('This controls the Schema.org markup Google sees for this page.', 'haupt-recruitment'); ?>
    </p>
    <?php
}

/**
 * Save the schema type meta box value
 */
add_action('save_post', function($post_id) {
    // Check nonce
    if (!isset($_POST['haupt_schema_type_nonce']) || !wp_verify_nonce($_POST['haupt_schema_type_nonce'], 'haupt_schema_type_save')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    
    // Save or delete
    if (isset($_POST['haupt_schema_type'])) {
        $schema_type = sanitize_text_field($_POST['haupt_schema_type']);
        if ($schema_type) {
            update_post_meta($post_id, 'haupt_schema_type', $schema_type);
        } else {
            delete_post_meta($post_id, 'haupt_schema_type');
        }
    }
});

/**
 * Get the effective schema type for the current page
 * Returns the manual override or empty string for auto-detect
 */
function haupt_get_page_schema_type() {
    if (!is_singular('page')) {
        return '';
    }
    return get_post_meta(get_the_ID(), 'haupt_schema_type', true);
}

/**
 * Generate custom schema markup based on selected type
 */
function haupt_get_custom_page_schema() {
    $custom_type = haupt_get_page_schema_type();
    
    if (!$custom_type) {
        return '';
    }
    
    $permalink = get_permalink();
    $title = get_the_title();
    $description = function_exists('haupt_get_seo_description') ? haupt_get_seo_description() : get_bloginfo('description');
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $custom_type,
        '@id' => $permalink . '#webpage',
        'url' => $permalink,
        'name' => $title,
        'description' => $description,
        'inLanguage' => get_locale(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'isPartOf' => [
            '@id' => home_url() . '#website',
        ],
    ];
    
    // Type-specific enhancements
    switch ($custom_type) {
        case 'Service':
            $schema['provider'] = [
                '@type' => 'Organization',
                '@id' => home_url() . '#organization',
            ];
            $schema['areaServed'] = [
                '@type' => 'Country',
                'name' => 'United Kingdom',
            ];
            break;
            
        case 'Product':
            $schema['brand'] = [
                '@type' => 'Organization',
                '@id' => home_url() . '#organization',
            ];
            break;
            
        case 'FAQPage':
            // FAQPage schema is already handled by haupt_get_faq_schema()
            // This custom type just overrides the @type on the WebPage
            break;
            
        case 'CollectionPage':
            $schema['mainEntity'] = [
                '@type' => 'ItemList',
                'itemListElement' => [],
            ];
            break;
    }
    
    if (has_post_thumbnail()) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url' => get_the_post_thumbnail_url(null, 'full'),
            'width' => 1200,
            'height' => 630,
        ];
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}
