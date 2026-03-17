# Haupt Recruitment 2026 WordPress Theme

Custom WordPress theme for Haupt Recruitment - a job recruitment website specializing in engineering and technical roles.

## Features

- **Custom Post Types**: Job Listings (`job`), Job Role Guides (`job_role`)
- **Taxonomies**: Job Categories, Job Sectors, Job Locations
- **Gutenberg Ready**: Full block editor support with custom styles
- **FAQ Accordion**: Auto-converts Gutenberg H2/H3 content to styled accordions
- **Schema Markup**: JSON-LD structured data for SEO (Organization, WebPage, FAQPage, JobPosting)
- **No ACF Required**: All theme options hardcoded in `inc/theme-options.php`
- **Modern CSS**: Blue/Teal color scheme with CSS variables

## URL Structure

```
/job-role/                    - Job Role Guides Archive
/job-role-category/{name}/    - Category Archive (10 sectors)
/job-role/{category}/{post}/  - Individual Job Role Guide
/jobs/                        - Job Listings Archive
/jobs/{job-post}/             - Individual Job Listing
```

## Installation

1. Upload to `/wp-content/themes/hauptrecruitment-2026/`
2. Activate theme in WordPress admin
3. Go to Appearance > Customize to set theme options
4. Create Categories under Job Role Guides > Categories (10 sectors)
5. Add Job Role Guides and assign categories

## Theme Options

- Company Info (phone, email, address)
- Social Media URLs
- Homepage Stats
- Homepage Hero Content

## Templates

- `template-homepage.php` - Homepage with hero, stats, sectors
- `template-job-role-expert.php` - Job Role Guide pages
- `template-jobs.php` - Job listings with filters
- `template-contact.php` - Contact page with form

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Version

1.0.4
