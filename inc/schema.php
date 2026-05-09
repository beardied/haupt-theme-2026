<?php
/**
 * Schema Markup Functions
 * Generates structured data for SEO and AI search optimization
 *
 * @package Haupt_Recruitment_2026
 */

/**
 * Get logo URL for schema markup
 * Uses customizer logo if available, falls back to theme default
 */
function haupt_get_schema_logo_url() {
    $logo_id = get_theme_mod('haupt_logo');
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        if ($logo_url) {
            return $logo_url;
        }
    }
    return HAUPT_URI . '/assets/images/logo.png';
}

/**
 * Get Organization Schema with full details
 */
function haupt_get_organization_schema() {
    $name = haupt_get_company_name();
    $url = home_url();
    $phone = haupt_get_phone();
    $email = haupt_get_email();
    $offices = haupt_get_offices();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => $url . '#organization',
        'name' => $name,
        'url' => $url,
        'description' => get_bloginfo('description'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => haupt_get_schema_logo_url(),
        ],
        'sameAs' => [],
        'knowsAbout' => [
            'UK Power Sector Recruitment',
            'Wind Energy Recruitment',
            'Offshore Recruitment',
            'HV & Cable Recruitment',
            'Substation Recruitment',
            'Transmission & Distribution',
        ],
    ];
    
    if ($phone) {
        $schema['telephone'] = $phone;
    }
    
    if ($email) {
        $schema['email'] = $email;
    }
    
    // Primary office address
    if (!empty($offices)) {
        $primary_office = $offices[0];
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $primary_office['address'] ?? '',
            'addressLocality' => $primary_office['city'] ?? '',
            'addressRegion' => $primary_office['region'] ?? '',
            'postalCode' => $primary_office['postcode'] ?? '',
            'addressCountry' => 'GB',
        ];
        
        // Add all offices as separate locations
        if (count($offices) > 1) {
            $schema['location'] = [];
            foreach ($offices as $office) {
                $schema['location'][] = [
                    '@type' => 'Place',
                    'name' => $office['name'] ?? $name . ' Office',
                    'telephone' => $office['phone'] ?? '',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $office['address'] ?? '',
                        'addressLocality' => $office['city'] ?? '',
                        'addressRegion' => $office['region'] ?? '',
                        'postalCode' => $office['postcode'] ?? '',
                        'addressCountry' => 'GB',
                    ],
                ];
            }
        }
    }
    
    // Social profiles
    $socials = [
        'linkedin' => 'linkedin_url',
        'twitter' => 'twitter_url',
        'facebook' => 'facebook_url',
        'instagram' => 'instagram_url',
    ];
    foreach ($socials as $platform => $option) {
        $social_url = haupt_get_social_url($platform);
        if ($social_url) {
            $schema['sameAs'][] = $social_url;
        }
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get LocalBusiness Schema
 */
function haupt_get_localbusiness_schema() {
    $name = haupt_get_company_name();
    $url = home_url();
    $phone = haupt_get_phone();
    $email = haupt_get_email();
    $offices = haupt_get_offices();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'EmploymentAgency',
        '@id' => $url . '#localbusiness',
        'name' => $name,
        'url' => $url,
        'description' => 'Specialist recruitment agency for UK Power, Wind, Offshore, HV & Cable sectors.',
        'image' => haupt_get_schema_logo_url(),
        'priceRange' => '££',
        'currenciesAccepted' => 'GBP',
        'paymentAccepted' => 'Cash, Credit Card, Bank Transfer',
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'United Kingdom',
        ],
        'serviceType' => [
            'Permanent Recruitment',
            'Contract Recruitment',
            'Executive Search',
            'Recruitment Consulting',
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Recruitment Services',
            'itemListElement' => [
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => 'Permanent Recruitment',
                        'description' => 'Finding permanent staff for power sector roles',
                    ],
                ],
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => 'Contract Recruitment',
                        'description' => 'Providing contract and temporary staffing solutions',
                    ],
                ],
            ],
        ],
    ];
    
    // Contact points for Google knowledge panel
    $contact_points = [];
    if ($phone) {
        $schema['telephone'] = $phone;
        $contact_points[] = [
            '@type' => 'ContactPoint',
            'telephone' => $phone,
            'contactType' => 'customer service',
            'availableLanguage' => ['English'],
        ];
    }
    if ($email) {
        $schema['email'] = $email;
        $contact_points[] = [
            '@type' => 'ContactPoint',
            'email' => $email,
            'contactType' => 'customer service',
            'availableLanguage' => ['English'],
        ];
    }
    if (!empty($contact_points)) {
        $schema['contactPoint'] = $contact_points;
    }
    
    // Opening hours - default business hours
    $schema['openingHoursSpecification'] = [
        [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '08:00',
            'closes' => '18:00',
        ],
    ];
    
    // Office locations
    if (!empty($offices)) {
        if (count($offices) === 1) {
            $office = $offices[0];
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $office['address'] ?? '',
                'addressLocality' => $office['city'] ?? '',
                'addressRegion' => $office['region'] ?? '',
                'postalCode' => $office['postcode'] ?? '',
                'addressCountry' => 'GB',
            ];
        } else {
            $schema['department'] = [];
            foreach ($offices as $office) {
                $schema['department'][] = [
                    '@type' => 'Place',
                    'name' => $office['name'] ?? $name . ' Office',
                    'telephone' => $office['phone'] ?? $phone,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $office['address'] ?? '',
                        'addressLocality' => $office['city'] ?? '',
                        'addressRegion' => $office['region'] ?? '',
                        'postalCode' => $office['postcode'] ?? '',
                        'addressCountry' => 'GB',
                    ],
                ];
            }
        }
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get WebSite Schema
 */
function haupt_get_website_schema() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => home_url() . '#website',
        'url' => home_url(),
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'publisher' => [
            '@id' => home_url() . '#organization',
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}'),
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get WebPage Schema
 * Uses haupt_get_seo_description() for rich descriptions
 */
function haupt_get_webpage_schema() {
    $description = function_exists('haupt_get_seo_description') ? haupt_get_seo_description() : haupt_get_meta_description();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        '@id' => get_permalink() . '#webpage',
        'url' => get_permalink(),
        'name' => wp_get_document_title(),
        'description' => $description,
        'inLanguage' => get_locale(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'isPartOf' => [
            '@id' => home_url() . '#website',
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
    
    if (is_front_page()) {
        $schema['about'] = [
            '@id' => home_url() . '#organization',
        ];
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get AboutPage Schema
 */
function haupt_get_aboutpage_schema() {
    if (!is_page('about-us')) {
        return '';
    }
    
    $offices = haupt_get_offices();
    $phone = haupt_get_phone();
    $email = haupt_get_email();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        '@id' => get_permalink() . '#aboutpage',
        'url' => get_permalink(),
        'name' => get_the_title(),
        'description' => function_exists('haupt_get_seo_description') ? haupt_get_seo_description() : get_bloginfo('description'),
        'inLanguage' => get_locale(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'isPartOf' => [
            '@id' => home_url() . '#website',
        ],
        'mainEntity' => [
            '@type' => 'Organization',
            '@id' => home_url() . '#organization',
            'name' => haupt_get_company_name(),
            'url' => home_url(),
            'logo' => haupt_get_schema_logo_url(),
            'sameAs' => [],
        ],
    ];
    
    // Add social profiles
    $socials = ['linkedin', 'twitter', 'facebook', 'instagram'];
    foreach ($socials as $platform) {
        $url = haupt_get_social_url($platform);
        if ($url) {
            $schema['mainEntity']['sameAs'][] = $url;
        }
    }
    
    if ($phone) {
        $schema['mainEntity']['telephone'] = $phone;
    }
    if ($email) {
        $schema['mainEntity']['email'] = $email;
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

/**
 * Get Article Schema for blog posts
 */
function haupt_get_article_schema() {
    if (!is_singular('post')) {
        return '';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        '@id' => get_permalink() . '#article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: get_bloginfo('description'),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => [
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url(get_the_author_meta('ID')),
        ],
        'publisher' => [
            '@id' => home_url() . '#organization',
        ],
        'isPartOf' => [
            '@id' => get_permalink() . '#webpage',
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
 * Google for Jobs compatible with auto-renewing validThrough
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
    $sector = haupt_get_meta('job_sector', $post_id);
    $experience = haupt_get_meta('experience_level', $post_id);
    $benefits = haupt_get_meta('benefits', $post_id);
    $company_name = haupt_get_meta('company_name', $post_id);
    
    // Map employment type to schema values
    $employment_type_map = [
        'permanent' => 'FULL_TIME',
        'contract' => 'CONTRACTOR',
        'temporary' => 'TEMPORARY',
        'part-time' => 'PART_TIME',
    ];
    $schema_employment_type = $employment_type_map[strtolower($employment_type)] ?? 'FULL_TIME';
    
    // validThrough: use closing date if set, otherwise 1 year from post date (auto-renews based on post date)
    if ($valid_through) {
        $valid_through_date = date('c', strtotime($valid_through));
    } else {
        $post_date = get_post_datetime($post_id);
        $valid_through_date = $post_date->format('c');
        $valid_through_date = date('c', strtotime($valid_through_date . ' +1 year'));
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        '@id' => get_permalink($post_id) . '#jobposting',
        'title' => get_the_title($post_id),
        'description' => wp_strip_all_tags(get_the_content(null, false, $post_id)),
        'datePosted' => get_the_date('c', $post_id),
        'validThrough' => $valid_through_date,
        'directApply' => true,
        'identifier' => [
            '@type' => 'PropertyValue',
            'name' => haupt_get_company_name(),
            'value' => 'HAUPT-' . $post_id,
        ],
        'hiringOrganization' => [
            '@type' => 'Organization',
            '@id' => home_url() . '#organization',
            'name' => $company_name ?: haupt_get_company_name(),
            'logo' => haupt_get_schema_logo_url(),
            'url' => home_url(),
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $location ?: 'United Kingdom',
                'addressCountry' => 'GB',
            ],
        ],
        'employmentType' => $schema_employment_type,
        'industry' => $sector ?: 'Energy & Power',
        'occupationalCategory' => $sector ?: 'Skilled Trades',
    ];
    
    // Salary parsing
    if ($salary) {
        // Try to extract salary range like "£40,000 - £60,000" or "40000 - 60000"
        preg_match('/£?([\d,]+)\s*-\s*£?([\d,]+)/i', $salary, $matches);
        if ($matches) {
            $min_salary = intval(str_replace(',', '', $matches[1]));
            $max_salary = intval(str_replace(',', '', $matches[2]));
            
            $schema['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'GBP',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $min_salary,
                    'maxValue' => $max_salary,
                    'unitText' => 'YEAR',
                ],
            ];
            
            $schema['estimatedSalary'] = [
                '@type' => 'MonetaryAmountDistribution',
                'currency' => 'GBP',
                'minValue' => $min_salary,
                'maxValue' => $max_salary,
                'unitText' => 'YEAR',
            ];
        } else {
            // Single value
            preg_match('/£?([\d,]+)/', $salary, $single_match);
            if ($single_match) {
                $value = intval(str_replace(',', '', $single_match[1]));
                $schema['baseSalary'] = [
                    '@type' => 'MonetaryAmount',
                    'currency' => 'GBP',
                    'value' => [
                        '@type' => 'QuantitativeValue',
                        'value' => $value,
                        'unitText' => 'YEAR',
                    ],
                ];
            }
        }
    }
    
    if ($experience) {
        $schema['experienceRequirements'] = $experience;
    }
    
    if ($benefits) {
        $schema['jobBenefits'] = $benefits;
    }
    
    // Job location type
    if (stripos($location, 'remote') !== false) {
        $schema['jobLocationType'] = 'TELECOMMUTE';
    }
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get Career Guide TechArticle Schema
 * TechArticle is stronger than generic Article for expert content
 */
function haupt_get_career_guide_schema() {
    if (!is_singular('role_expertise')) {
        return '';
    }
    
    $reading_time = haupt_get_meta('reading_time', null, '5 min read');
    $salary_range = haupt_get_meta('salary_range');
    $experience_level = haupt_get_meta('experience_level');
    $qualifications = haupt_get_meta('required_qualifications');
    $parent_id = wp_get_post_parent_id(get_the_ID());
    $sector = '';
    
    if ($parent_id) {
        $sector = get_the_title($parent_id);
    }
    
    // Get category terms for better context
    $categories = get_the_terms(get_the_ID(), 'role_expertise_category');
    $category_names = [];
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $category_names[] = $cat->name;
        }
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'TechArticle',
        '@id' => get_permalink() . '#techarticle',
        'headline' => get_the_title(),
        'name' => get_the_title(),
        'description' => get_the_excerpt() ?: get_bloginfo('description'),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => [
            '@type' => 'Organization',
            'name' => haupt_get_company_name(),
            '@id' => home_url() . '#organization',
        ],
        'publisher' => [
            '@id' => home_url() . '#organization',
        ],
        'isPartOf' => [
            '@id' => get_permalink() . '#webpage',
        ],
        'articleSection' => $sector ?: 'Career Guides',
        'proficiencyLevel' => $experience_level ?: 'Professional',
        'inLanguage' => 'en-GB',
        'audience' => [
            '@type' => 'Audience',
            'audienceType' => 'Job Seekers in UK Energy Sector',
        ],
        'about' => [
            '@type' => 'Thing',
            'name' => $sector ?: 'Power Industry Careers',
            'description' => 'Career information and guidance for ' . get_the_title() . ' roles in the UK energy sector.',
        ],
    ];
    
    // Add expertise / dependencies (qualifications)
    if ($qualifications) {
        $schema['dependencies'] = $qualifications;
    }
    
    // Add salary info if available
    if ($salary_range) {
        $schema['about']['description'] .= ' Typical salary range: ' . $salary_range . '.';
    }
    
    // Add keywords from categories
    if (!empty($category_names)) {
        $schema['keywords'] = implode(', ', $category_names);
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

/**
 * Get ContactPage Schema
 */
function haupt_get_contactpage_schema() {
    if (!is_page('contact')) {
        return '';
    }
    
    $offices = haupt_get_offices();
    $phones = [];
    $emails = [];
    
    foreach ($offices as $office) {
        if (!empty($office['phone'])) {
            $phones[] = $office['phone'];
        }
        if (!empty($office['email'])) {
            $emails[] = $office['email'];
        }
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        '@id' => get_permalink() . '#contactpage',
        'url' => get_permalink(),
        'name' => get_the_title(),
        'description' => 'Contact ' . haupt_get_company_name() . ' for recruitment services in the UK power sector.',
        'mainEntity' => [
            '@type' => 'Organization',
            '@id' => home_url() . '#organization',
            'telephone' => $phones ?: haupt_get_phone(),
            'email' => $emails ?: haupt_get_email(),
        ],
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get BreadcrumbList Schema
 */
function haupt_get_breadcrumb_schema() {
    $breadcrumbs = haupt_get_breadcrumbs_array();
    
    if (empty($breadcrumbs)) {
        return '';
    }
    
    $items = [];
    $position = 1;
    
    foreach ($breadcrumbs as $crumb) {
        $item = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $crumb['title'],
        ];
        
        if (!empty($crumb['url'])) {
            $item['item'] = $crumb['url'];
        }
        
        $items[] = $item;
        $position++;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        '@id' => get_permalink() . '#breadcrumb',
        'itemListElement' => $items,
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Helper function to get breadcrumbs as array
 */
function haupt_get_breadcrumbs_array() {
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'title' => 'Home',
        'url' => home_url(),
    ];
    
    if (is_singular('job')) {
        $breadcrumbs[] = [
            'title' => 'Jobs',
            'url' => get_post_type_archive_link('job'),
        ];
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_singular('role_expertise')) {
        $parent_id = wp_get_post_parent_id(get_the_ID());
        if ($parent_id) {
            $breadcrumbs[] = [
                'title' => get_the_title($parent_id),
                'url' => get_permalink($parent_id),
            ];
        } else {
            $breadcrumbs[] = [
                'title' => 'Career Guides',
                'url' => get_post_type_archive_link('role_expertise'),
            ];
        }
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_singular()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        if ($post_type_obj && $post_type !== 'page') {
            $breadcrumbs[] = [
                'title' => $post_type_obj->label,
                'url' => get_post_type_archive_link($post_type),
            ];
        }
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_archive()) {
        $breadcrumbs[] = [
            'title' => get_the_archive_title(),
            'url' => '',
        ];
    }
    
    return $breadcrumbs;
}

/**
 * Get FAQPage Schema from Gutenberg Content
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
        '@id' => get_permalink() . '#faqpage',
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
 * Get ItemList Schema for job archives
 */
function haupt_get_job_list_schema() {
    if (!is_post_type_archive('job')) {
        return '';
    }
    
    global $wp_query;
    $items = [];
    $position = 1;
    
    while (have_posts()) : the_post();
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'url' => get_permalink(),
            'name' => get_the_title(),
        ];
        $position++;
    endwhile;
    
    rewind_posts();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        '@id' => get_post_type_archive_link('job') . '#itemlist',
        'itemListElement' => $items,
    ];
    
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Get meta description helper
 * Falls back to haupt_get_seo_description() if available (template-functions.php)
 */
function haupt_get_meta_description() {
    if (function_exists('haupt_get_seo_description')) {
        return haupt_get_seo_description();
    }
    
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            return wp_trim_words($excerpt, 30, '...');
        }
    }
    
    if (is_post_type_archive('job')) {
        return 'Browse current job vacancies in the UK power, wind, offshore, HV & cable sectors. Find your next career opportunity with Haupt Recruitment.';
    }
    
    if (is_post_type_archive('role_expertise')) {
        return 'Career guides for power industry professionals. Learn about roles, salaries, and career progression in the UK energy sector.';
    }
    
    return get_bloginfo('description');
}

/**
 * Output all schema
 */
add_action('wp_head', function() {
    // Organization schema (always output)
    echo haupt_get_organization_schema() . "\n";
    
    // LocalBusiness schema for homepage, contact and about pages
    if (is_front_page() || is_page('contact') || is_page('about-us')) {
        echo haupt_get_localbusiness_schema() . "\n";
    }
    
    // WebSite schema
    echo haupt_get_website_schema() . "\n";
    
    // Custom page schema type override (if set in editor)
    $custom_schema = '';
    if (function_exists('haupt_get_custom_page_schema')) {
        $custom_schema = haupt_get_custom_page_schema();
    }
    
    if ($custom_schema) {
        // User has selected a custom schema type - use it instead of generic WebPage
        echo $custom_schema . "\n";
    } else {
        // Default WebPage schema
        echo haupt_get_webpage_schema() . "\n";
    }
    
    // Breadcrumb schema
    echo haupt_get_breadcrumb_schema() . "\n";
    
    // Article schema for posts
    if (is_singular('post')) {
        echo haupt_get_article_schema() . "\n";
    }
    
    // JobPosting schema for job listings
    if (is_singular('job')) {
        echo haupt_get_jobposting_schema() . "\n";
    }
    
    // Career guide schema
    if (is_singular('role_expertise')) {
        echo haupt_get_career_guide_schema() . "\n";
    }
    
    // Contact page schema
    if (is_page('contact')) {
        echo haupt_get_contactpage_schema() . "\n";
    }
    
    // About page schema
    if (is_page('about-us')) {
        echo haupt_get_aboutpage_schema() . "\n";
    }
    
    // FAQ schema (if present on page)
    $faq_schema = haupt_get_faq_schema();
    if ($faq_schema) {
        echo $faq_schema . "\n";
    }
    
    // Job list schema for archives
    if (is_post_type_archive('job')) {
        echo haupt_get_job_list_schema() . "\n";
    }
}, 5);

/**
 * Add schema types to body class for targeting
 */
add_filter('body_class', function($classes) {
    if (is_singular('job')) {
        $classes[] = 'schema-jobposting';
    }
    if (is_singular('role_expertise')) {
        $classes[] = 'schema-techarticle';
    }
    if (is_front_page()) {
        $classes[] = 'schema-homepage';
    }
    return $classes;
});
