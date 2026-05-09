<?php
/**
 * Schema Markup Functions
 * Clean, minimal structured data — NO BLOAT
 *
 * @package Haupt_Recruitment_2026
 */

// ============================================
// ORGANIZATION (Homepage, About, Contact only)
// ============================================
function haupt_get_organization_schema() {
    $name = haupt_get_company_name();
    $url  = home_url();
    $phone = haupt_get_phone();
    $email = haupt_get_email();
    $offices = haupt_get_offices();

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => $url . '#organization',
        'name'     => $name,
        'url'      => $url,
        'logo'     => [
            '@type' => 'ImageObject',
            'url'   => haupt_get_schema_logo_url(),
        ],
        'sameAs'   => [],
    ];

    if ($phone) $schema['telephone'] = $phone;
    if ($email) $schema['email']     = $email;

    // Social profiles
    foreach (['linkedin','twitter','facebook','instagram'] as $platform) {
        $social_url = haupt_get_social_url($platform);
        if ($social_url) $schema['sameAs'][] = $social_url;
    }

    // Primary office address + all locations
    if (!empty($offices)) {
        $primary = $offices[0];
        $schema['address'] = [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $primary['address'] ?? '',
            'addressLocality' => $primary['city'] ?? '',
            'addressRegion'   => $primary['region'] ?? '',
            'postalCode'      => $primary['postcode'] ?? '',
            'addressCountry'  => 'GB',
        ];

        if (count($offices) > 1) {
            $schema['location'] = [];
            foreach ($offices as $office) {
                $schema['location'][] = [
                    '@type'         => 'Place',
                    'name'          => $office['name'] ?? $name . ' Office',
                    'telephone'     => $office['phone'] ?? '',
                    'address'       => [
                        '@type'           => 'PostalAddress',
                        'streetAddress'   => $office['address'] ?? '',
                        'addressLocality' => $office['city'] ?? '',
                        'addressRegion'   => $office['region'] ?? '',
                        'postalCode'      => $office['postcode'] ?? '',
                        'addressCountry'  => 'GB',
                    ],
                ];
            }
        }
    }

    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

function haupt_get_schema_logo_url() {
    $logo_id = get_theme_mod('haupt_logo');
    if ($logo_id) {
        $url = wp_get_attachment_image_url($logo_id, 'full');
        if ($url) return $url;
    }
    return HAUPT_URI . '/assets/images/logo.png';
}

// ============================================
// WEBSITE (All pages)
// ============================================
function haupt_get_website_schema() {
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        '@id'      => home_url() . '#website',
        'url'      => home_url(),
        'name'     => get_bloginfo('name'),
        'publisher' => ['@id' => home_url() . '#organization'],
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

// ============================================
// WEBPAGE (All pages — or custom override)
// ============================================
function haupt_get_webpage_schema() {
    $desc = function_exists('haupt_get_seo_description') ? haupt_get_seo_description() : haupt_get_meta_description();

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'WebPage',
        '@id'      => get_permalink() . '#webpage',
        'url'      => get_permalink(),
        'name'     => wp_get_document_title(),
        'description' => $desc,
        'inLanguage'  => get_locale(),
        'isPartOf'    => ['@id' => home_url() . '#website'],
    ];

    if (has_post_thumbnail()) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url'   => get_the_post_thumbnail_url(null, 'full'),
            'width' => 1200,
            'height'=> 630,
        ];
    }

    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

// ============================================
// BREADCRUMBS (All pages)
// ============================================
function haupt_get_breadcrumb_schema() {
    $crumbs = haupt_get_breadcrumbs_array();
    if (empty($crumbs)) return '';

    $items = [];
    $pos = 1;
    foreach ($crumbs as $c) {
        $item = ['@type' => 'ListItem', 'position' => $pos, 'name' => $c['title']];
        if (!empty($c['url'])) $item['item'] = $c['url'];
        $items[] = $item;
        $pos++;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        '@id'      => get_permalink() . '#breadcrumb',
        'itemListElement' => $items,
    ];
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

function haupt_get_breadcrumbs_array() {
    $b = [['title'=>'Home','url'=>home_url()]];

    if (is_singular('job')) {
        $b[] = ['title'=>'Jobs','url'=>get_post_type_archive_link('job')];
        $b[] = ['title'=>get_the_title(),'url'=>''];
    } elseif (is_singular('role_expertise')) {
        $parent = wp_get_post_parent_id(get_the_ID());
        if ($parent) {
            $b[] = ['title'=>get_the_title($parent),'url'=>get_permalink($parent)];
        } else {
            $b[] = ['title'=>'Career Guides','url'=>get_post_type_archive_link('role_expertise')];
        }
        $b[] = ['title'=>get_the_title(),'url'=>''];
    } elseif (is_singular()) {
        $pt = get_post_type_object(get_post_type());
        if ($pt && get_post_type() !== 'page') {
            $b[] = ['title'=>$pt->label,'url'=>get_post_type_archive_link(get_post_type())];
        }
        $b[] = ['title'=>get_the_title(),'url'=>''];
    } elseif (is_archive()) {
        $b[] = ['title'=>get_the_archive_title(),'url'=>''];
    }
    return $b;
}

// ============================================
// JOB POSTING (Job single pages only)
// ============================================
function haupt_get_jobposting_schema($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    if (get_post_type($post_id) !== 'job') return '';

    $location = haupt_get_meta('job_location', $post_id);
    $salary   = haupt_get_meta('salary', $post_id);
    $emp_type = haupt_get_meta('employment_type', $post_id) ?: 'FULL_TIME';
    $valid    = haupt_get_meta('closing_date', $post_id);
    $sector   = haupt_get_meta('job_sector', $post_id);
    $exp      = haupt_get_meta('experience_level', $post_id);
    $benefits = haupt_get_meta('benefits', $post_id);
    $company  = haupt_get_meta('company_name', $post_id);

    $map = ['permanent'=>'FULL_TIME','contract'=>'CONTRACTOR','temporary'=>'TEMPORARY','part-time'=>'PART_TIME'];
    $emp_schema = $map[strtolower($emp_type)] ?? 'FULL_TIME';

    // validThrough: closing date or +1 year from post date
    if ($valid) {
        $valid_date = date('c', strtotime($valid));
    } else {
        $valid_date = date('c', strtotime(get_the_date('c', $post_id) . ' +1 year'));
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'JobPosting',
        '@id'      => get_permalink($post_id) . '#jobposting',
        'title'    => get_the_title($post_id),
        'description' => wp_strip_all_tags(get_the_content(null, false, $post_id)),
        'datePosted'  => get_the_date('c', $post_id),
        'validThrough'=> $valid_date,
        'directApply' => true,
        'identifier'  => [
            '@type' => 'PropertyValue',
            'name'  => haupt_get_company_name(),
            'value' => 'HAUPT-' . $post_id,
        ],
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name'  => $company ?: haupt_get_company_name(),
            'logo'  => haupt_get_schema_logo_url(),
            'url'   => home_url(),
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $location ?: 'United Kingdom',
                'addressCountry'  => 'GB',
            ],
        ],
        'employmentType' => $emp_schema,
        'industry' => $sector ?: 'Energy & Power',
    ];

    // Salary
    if ($salary) {
        preg_match('/£?([\d,]+)\s*-\s*£?([\d,]+)/i', $salary, $m);
        if ($m) {
            $min = intval(str_replace(',','',$m[1]));
            $max = intval(str_replace(',','',$m[2]));
            $schema['baseSalary'] = [
                '@type'    => 'MonetaryAmount',
                'currency' => 'GBP',
                'value'    => ['@type'=>'QuantitativeValue','minValue'=>$min,'maxValue'=>$max,'unitText'=>'YEAR'],
            ];
        } else {
            preg_match('/£?([\d,]+)/', $salary, $s);
            if ($s) {
                $schema['baseSalary'] = [
                    '@type'    => 'MonetaryAmount',
                    'currency' => 'GBP',
                    'value'    => ['@type'=>'QuantitativeValue','value'=>intval(str_replace(',','',$s[1])),'unitText'=>'YEAR'],
                ];
            }
        }
    }

    if ($exp)      $schema['experienceRequirements'] = $exp;
    if ($benefits) $schema['jobBenefits'] = $benefits;
    if (stripos($location, 'remote') !== false) $schema['jobLocationType'] = 'TELECOMMUTE';

    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

// ============================================
// TECHARTICLE (Career guides only)
// ============================================
function haupt_get_career_guide_schema() {
    if (!is_singular('role_expertise')) return '';

    $sector = '';
    $parent = wp_get_post_parent_id(get_the_ID());
    if ($parent) $sector = get_the_title($parent);

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'TechArticle',
        '@id'      => get_permalink() . '#techarticle',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: get_bloginfo('description'),
        'url'      => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified'  => get_the_modified_date('c'),
        'author'   => ['@id' => home_url() . '#organization'],
        'publisher'=> ['@id' => home_url() . '#organization'],
        'isPartOf' => ['@id' => get_permalink() . '#webpage'],
        'articleSection' => $sector ?: 'Career Guides',
        'inLanguage' => 'en-GB',
    ];

    if (has_post_thumbnail()) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url'   => get_the_post_thumbnail_url(null, 'full'),
            'width' => 1200,
            'height'=> 630,
        ];
    }

    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

// ============================================
// ARTICLE (Blog posts only)
// ============================================
function haupt_get_article_schema() {
    if (!is_singular('post')) return '';

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Article',
        '@id'      => get_permalink() . '#article',
        'headline' => get_the_title(),
        'description' => get_the_excerpt() ?: get_bloginfo('description'),
        'url'      => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified'  => get_the_modified_date('c'),
        'author'   => ['@id' => home_url() . '#organization'],
        'publisher'=> ['@id' => home_url() . '#organization'],
        'isPartOf' => ['@id' => get_permalink() . '#webpage'],
    ];

    if (has_post_thumbnail()) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url'   => get_the_post_thumbnail_url(null, 'full'),
            'width' => 1200,
            'height'=> 630,
        ];
    }

    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

// ============================================
// FAQ PAGE (Any page with FAQ content)
// ============================================
function haupt_get_faq_schema() {
    // Only output FAQ schema on singular pages where the content itself has FAQs
    if (!is_singular()) return '';
    global $post;
    if (!$post) return '';

    $content = $post->post_content;
    $blocks = parse_blocks($content);
    $in_faq = false;
    $q = null;
    $a = '';
    $items = [];

    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/heading' && ($block['attrs']['level'] ?? 0) === 2) {
            $in_faq = stripos(wp_strip_all_tags(render_block($block)), 'faq') !== false;
            continue;
        }
        if (!$in_faq) continue;

        if ($block['blockName'] === 'core/heading' && ($block['attrs']['level'] ?? 0) === 3) {
            if ($q && $a) $items[] = ['@type'=>'Question','name'=>$q,'acceptedAnswer'=>['@type'=>'Answer','text'=>wp_strip_all_tags($a)]];
            $q = wp_strip_all_tags(render_block($block));
            $a = '';
        } elseif ($block['blockName'] === 'core/paragraph' && $q) {
            $a .= ' ' . render_block($block);
        } elseif ($q && !in_array($block['blockName'], ['core/paragraph','core/heading'])) {
            if ($a) $items[] = ['@type'=>'Question','name'=>$q,'acceptedAnswer'=>['@type'=>'Answer','text'=>wp_strip_all_tags($a)]];
            $q = null; $a = '';
        }
    }
    if ($q && $a) $items[] = ['@type'=>'Question','name'=>$q,'acceptedAnswer'=>['@type'=>'Answer','text'=>wp_strip_all_tags($a)]];

    // Regex fallback
    if (empty($items)) $items = haupt_extract_faq_from_html($content);
    if (empty($items)) return '';

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        '@id'      => get_permalink() . '#faqpage',
        'mainEntity' => $items,
    ];
    return '<script type="application/ld+json">' . wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
}

function haupt_extract_faq_from_html($content) {
    $items = [];
    if (!preg_match('/<h2[^>]*>.*?faq.*?<\/h2>(.+)/is', $content, $m)) return $items;
    preg_match_all('/<h3[^>]*>(.*?)<\/h3>(.*?)(?=<h[23]|$)/is', $m[1], $m, PREG_SET_ORDER);
    foreach ($m as $match) {
        $q = wp_strip_all_tags($match[1]);
        $a = wp_strip_all_tags($match[2]);
        if ($q && $a) $items[] = ['@type'=>'Question','name'=>$q,'acceptedAnswer'=>['@type'=>'Answer','text'=>$a]];
    }
    return $items;
}

// ============================================
// META DESCRIPTION HELPER
// ============================================
function haupt_get_meta_description() {
    if (function_exists('haupt_get_seo_description')) return haupt_get_seo_description();
    if (is_singular() && ($ex = get_the_excerpt())) return wp_trim_words($ex, 30, '...');
    if (is_post_type_archive('job')) return 'Browse current job vacancies in the UK power sector.';
    if (is_post_type_archive('role_expertise')) return 'Career guides for power industry professionals.';
    return get_bloginfo('description');
}

// ============================================
// OUTPUT ALL SCHEMA IN WP_HEAD
// ============================================
add_action('wp_head', function() {

    // Organization: Homepage, About, Contact only
    if (is_front_page() || is_page('contact') || is_page('about-us')) {
        echo haupt_get_organization_schema() . "\n";
    }

    // WebSite: all pages
    echo haupt_get_website_schema() . "\n";

    // WebPage (or custom type from editor): all pages
    $custom = function_exists('haupt_get_custom_page_schema') ? haupt_get_custom_page_schema() : '';
    echo ($custom ?: haupt_get_webpage_schema()) . "\n";

    // Breadcrumbs: all pages
    echo haupt_get_breadcrumb_schema() . "\n";

    // Article: blog posts
    if (is_singular('post')) {
        echo haupt_get_article_schema() . "\n";
    }

    // JobPosting: job singles
    if (is_singular('job')) {
        echo haupt_get_jobposting_schema() . "\n";
    }

    // TechArticle: career guides
    if (is_singular('role_expertise')) {
        echo haupt_get_career_guide_schema() . "\n";
    }

    // FAQ: any page with FAQ content
    $faq = haupt_get_faq_schema();
    if ($faq) echo $faq . "\n";

}, 5);
