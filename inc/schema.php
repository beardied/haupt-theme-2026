<?php
/**
 * Schema Markup Functions
 * Generates structured data for SEO and AI search optimization
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Get Organization Schema
 */
function haupt_get_organization_schema() {
    $name = get_bloginfo('name');
    $url = home_url();
    $logo = HAUPT_URI . '/assets/images/logo.png';
    $phone = haupt_get_phone();
    $email = haupt_get_email();
    $address = haupt_get_address();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $name,
        'url' => $url,
        'logo' => $logo,
        'description' => get_bloginfo('description'),
        'sameAs' => [],
    ];
    
    if ($phone) {
        $schema['telephone'] = $phone;
    }
    
    if ($email) {
        $schema['email'] = $email;
    }
    
    if ($address) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
        ];
    }
    
    // Social profiles
    $socials = ['linkedin_url', 'twitter_url', 'facebook_url'];
    foreach ($socials as $social) {
        $url = haupt_get_option($social . '_url');
        if ($url) {
            $schema['sameAs'][] = $url;
        }
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get WebPage Schema
 */
function haupt_get_webpage_schema() {
    if (!is_singular() && !is_front_page()) {
        return '';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        '@id' => get_permalink(),
        'url' => get_permalink(),
        'name' => wp_get_document_title(),
        'description' => get_the_excerpt() ?: get_bloginfo('description'),
        'inLanguage' => get_locale(),
    ];
    
    if (is_front_page()) {
        $schema['@type'] = 'WebSite';
        $schema['potentialAction'] = [
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ];
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get Article Schema (for blog posts)
 */
function haupt_get_article_schema() {
    if (!is_singular('post')) {
        return '';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt(),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => [
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url(get_the_author_meta('ID')),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => HAUPT_URI . '/assets/images/logo.png',
            ],
        ],
    ];
    
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

/**
 * Get JobPosting Schema
 */
function haupt_get_jobposting_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (get_post_type($post_id) !== 'job') {
        return '';
    }
    
    $location = haupt_get_meta('job_location', $post_id);
    $salary = haupt_get_meta('salary', $post_id);
    $job_type = haupt_get_meta('job_type', $post_id);
    $employment_type = haupt_get_meta('employment_type', $post_id) ?: 'FULL_TIME';
    $valid_through = haupt_get_meta('closing_date', $post_id);
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => get_the_title($post_id),
        'description' => get_the_content(null, false, $post_id),
        'datePosted' => get_the_date('c', $post_id),
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'sameAs' => home_url(),
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $location ?: 'United Kingdom',
                'addressCountry' => 'GB',
            ],
        ],
    ];
    
    if ($salary) {
        // Try to extract salary range
        preg_match('/£?([\d,]+)\s*-\s*£?([\d,]+)/', $salary, $matches);
        if ($matches) {
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'GBP',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => intval(str_replace(',', '', $matches[1])),
                    'maxValue' => intval(str_replace(',', '', $matches[2])),
                    'unitText' => 'YEAR',
                ],
            ];
        }
        $schema['estimatedSalary'] = [
            '@type' => 'MonetaryAmount',
            'currency' => 'GBP',
            'value' => [
                '@type' => 'QuantitativeValue',
                'value' => $salary,
            ],
        ];
    }
    
    if ($employment_type) {
        $schema['employmentType'] = $employment_type;
    }
    
    if ($valid_through) {
        $schema['validThrough'] = date('c', strtotime($valid_through));
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get FAQPage Schema from Gutenberg Content
 * Extracts FAQ section and generates structured data
 */
function haupt_get_faq_schema() {
    global $post;
    if (!$post) return '';
    
    $content = $post->post_content;
    $faq_items = [];
    
    // Parse Gutenberg blocks to find FAQ section
    $blocks = parse_blocks($content);
    $in_faq_section = false;
    $current_question = null;
    $current_answer = '';
    
    foreach ($blocks as $block) {
        // Check for H2 containing "FAQ"
        if ($block['blockName'] === 'core/heading' && isset($block['attrs']['level']) && $block['attrs']['level'] === 2) {
            $text = wp_strip_all_tags(render_block($block));
            if (stripos($text, 'faq') !== false) {
                $in_faq_section = true;
                continue;
            } else {
                $in_faq_section = false;
            }
        }
        
        // If we're in the FAQ section, collect questions and answers
        if ($in_faq_section) {
            // H3 = Question
            if ($block['blockName'] === 'core/heading' && isset($block['attrs']['level']) && $block['attrs']['level'] === 3) {
                // Save previous Q&A if exists
                if ($current_question && $current_answer) {
                    $faq_items[] = [
                        '@type' => 'Question',
                        'name' => $current_question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => wp_strip_all_tags($current_answer),
                        ],
                    ];
                }
                $current_question = wp_strip_all_tags(render_block($block));
                $current_answer = '';
            }
            // Paragraph = Answer (accumulate multiple paragraphs)
            elseif ($block['blockName'] === 'core/paragraph' && $current_question) {
                $current_answer .= ' ' . render_block($block);
            }
            // Any other block ends the current answer
            elseif ($current_question && !in_array($block['blockName'], ['core/paragraph', 'core/heading'])) {
                if ($current_answer) {
                    $faq_items[] = [
                        '@type' => 'Question',
                        'name' => $current_question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => wp_strip_all_tags($current_answer),
                        ],
                    ];
                }
                $current_question = null;
                $current_answer = '';
            }
        }
    }
    
    // Don't forget the last Q&A
    if ($current_question && $current_answer) {
        $faq_items[] = [
            '@type' => 'Question',
            'name' => $current_question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => wp_strip_all_tags($current_answer),
            ],
        ];
    }
    
    // If no FAQ found via blocks, try regex fallback
    if (empty($faq_items)) {
        $faq_items = haupt_extract_faq_from_html($content);
    }
    
    if (empty($faq_items)) {
        return '';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faq_items,
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Extract FAQ from HTML content (fallback)
 */
function haupt_extract_faq_from_html($content) {
    $faq_items = [];
    
    // Find FAQ section
    if (!preg_match('/<h2[^>]*>.*?faq.*?<\/h2>(.+)/is', $content, $matches)) {
        return $faq_items;
    }
    
    $faq_section = $matches[1];
    
    // Find all H3 + following P patterns
    preg_match_all('/<h3[^>]*>(.*?)<\/h3>(.*?)(?=<h[23]|$)/is', $faq_section, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $question = wp_strip_all_tags($match[1]);
        $answer = wp_strip_all_tags($match[2]);
        
        if ($question && $answer) {
            $faq_items[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }
    }
    
    return $faq_items;
}

/**
 * Get BreadcrumbList Schema
 */
function haupt_get_breadcrumb_schema($breadcrumbs) {
    if (empty($breadcrumbs)) {
        return '';
    }
    
    $items = [];
    $position = 1;
    
    foreach ($breadcrumbs as $crumb) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $crumb['title'],
            'item' => $crumb['url'],
        ];
        $position++;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Output all schema
 */
add_action('wp_head', function() {
    // Organization schema (always output)
    echo haupt_get_organization_schema() . "\n";
    
    // WebPage schema
    echo haupt_get_webpage_schema() . "\n";
    
    // Article schema for posts
    if (is_singular('post')) {
        echo haupt_get_article_schema() . "\n";
    }
    
    // JobPosting schema for job listings
    if (is_singular('job')) {
        echo haupt_get_jobposting_schema() . "\n";
    }
}, 5);

/**
 * Add schema to job archive
 */
add_action('haupt_before_job_archive', function() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => [],
    ];
    
    global $wp_query;
    $position = 1;
    
    while (have_posts()) : the_post();
        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => get_permalink(),
        ];
        $position++;
    endwhile;
    
    rewind_posts();
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
});

/**
 * Add HowTo schema for expert guides
 * Note: HowTo content should be added via Gutenberg blocks
 */
function haupt_get_howto_schema() {
    return '';
}
