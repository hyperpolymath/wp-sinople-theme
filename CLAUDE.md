# CLAUDE.md - WordPress Sinople Theme

## Project Overview

This is a WordPress theme called "Sinople" (from the heraldic term for green). It is a custom WordPress theme designed to be modern, performant, and maintainable.

## Project Structure

```
wp-sinople-theme/
├── style.css                 # Main stylesheet with theme header
├── functions.php            # Theme functions and hooks
├── index.php                # Main template file
├── header.php               # Header template
├── footer.php               # Footer template
├── sidebar.php              # Sidebar template
├── single.php               # Single post template
├── page.php                 # Page template
├── archive.php              # Archive template
├── search.php               # Search results template
├── 404.php                  # 404 error page template
├── comments.php             # Comments template
├── screenshot.png           # Theme screenshot (1200x900px)
├── assets/
│   ├── css/                 # Additional stylesheets
│   ├── js/                  # JavaScript files
│   └── images/              # Theme images
├── template-parts/          # Reusable template parts
├── inc/                     # PHP includes and functionality
└── languages/               # Translation files
```

## Key Technologies

- **WordPress**: 6.0+
- **PHP**: 7.4+ (8.0+ recommended)
- **CSS**: Modern CSS3 with potential preprocessor (SCSS/SASS)
- **JavaScript**: ES6+ (vanilla JS or minimal dependencies)
- **Build Tools**: May include npm/webpack for asset compilation

## WordPress Theme Requirements

### Required Files
- `style.css` - Must include theme header comment block
- `index.php` - Fallback template file
- `screenshot.png` - Theme preview (1200x900px recommended)

### Theme Header Format (style.css)
```css
/*
Theme Name: Sinople
Theme URI: https://example.com/sinople
Author: [Author Name]
Author URI: https://example.com
Description: A modern, green-themed WordPress theme
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: sinople
Tags: custom-background, custom-logo, custom-menu, featured-images
*/
```

## Development Guidelines

### PHP Coding Standards
- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use proper escaping functions: `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitize user inputs: `sanitize_text_field()`, `wp_kses_post()`
- Use WordPress functions over PHP alternatives when available
- Prefix all custom functions with `sinople_`

### Security Best Practices
- Always escape output
- Sanitize and validate input
- Use nonces for forms
- Check user capabilities before privileged operations
- Never trust user input or external data

### Template Hierarchy
WordPress follows a specific template hierarchy. Key templates:
1. `front-page.php` - Front page (if set)
2. `home.php` - Blog posts index
3. `single.php` - Single post
4. `page.php` - Single page
5. `archive.php` - Archive pages
6. `category.php` - Category archives
7. `tag.php` - Tag archives
8. `search.php` - Search results
9. `404.php` - Not found
10. `index.php` - Fallback for all

### Theme Features to Support
- Custom logo
- Custom menus (navigation)
- Widget areas (sidebars)
- Featured images (post thumbnails)
- Custom backgrounds
- Editor styles
- Block editor (Gutenberg) support
- Responsive design
- Accessibility (WCAG 2.1 AA compliance)

### WordPress Functions & Hooks

#### Essential Theme Setup (functions.php)
```php
function sinople_theme_setup() {
    // Add theme support features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    // Register navigation menus
    register_nav_menus();

    // Add editor styles
    add_theme_support('editor-styles');
}
add_action('after_setup_theme', 'sinople_theme_setup');
```

## Asset Management

### Enqueuing Styles and Scripts
Always use `wp_enqueue_style()` and `wp_enqueue_script()` - never hardcode in templates.

```php
function sinople_enqueue_assets() {
    wp_enqueue_style('sinople-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('sinople-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'sinople_enqueue_assets');
```

## Internationalization (i18n)

- Text domain: `sinople`
- Use translation functions: `__()`, `_e()`, `_x()`, `esc_html__()`, `esc_html_e()`
- Example: `esc_html_e('Hello World', 'sinople');`

## Testing Checklist

- [ ] Test on WordPress 6.0+
- [ ] Test on PHP 7.4, 8.0, 8.1+
- [ ] Validate HTML/CSS
- [ ] Check responsive design (mobile, tablet, desktop)
- [ ] Test accessibility with screen readers
- [ ] Verify all template files work correctly
- [ ] Check browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Test with WordPress Theme Check plugin
- [ ] Verify proper escaping and sanitization

## Common WordPress Functions

### Template Tags
- `get_header()` - Include header.php
- `get_footer()` - Include footer.php
- `get_sidebar()` - Include sidebar.php
- `get_template_part()` - Include template parts
- `wp_head()` - Required in header
- `wp_footer()` - Required in footer
- `body_class()` - Add body classes
- `post_class()` - Add post classes

### Content Functions
- `the_title()` - Display post title
- `the_content()` - Display post content
- `the_excerpt()` - Display post excerpt
- `the_permalink()` - Display post URL
- `the_post_thumbnail()` - Display featured image
- `the_time()` - Display post date/time
- `the_author()` - Display post author

## Debugging

Enable debugging in wp-config.php during development:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## File Permissions

Recommended permissions:
- Directories: 755
- PHP files: 644
- wp-config.php: 600 (if possible)

## Git Workflow

- Main development branch: `claude/create-claude-md-01XMBAxFdTUTCqsvscUtvXqm`
- Commit messages should be descriptive and follow conventional commit format
- Always test before committing
- Push to origin when ready

## Resources

- [WordPress Theme Developer Handbook](https://developer.wordpress.org/themes/)
- [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Theme Review Guidelines](https://make.wordpress.org/themes/handbook/review/)

## Notes for Claude

- Always escape output with appropriate WordPress functions
- Use WordPress functions over PHP alternatives (e.g., `wp_remote_get()` instead of `curl`)
- Follow WordPress naming conventions and coding standards
- Test templates with actual WordPress installation when possible
- Consider backward compatibility with older WordPress/PHP versions
- Prioritize accessibility and responsive design
- Use semantic HTML5 markup
- Keep the theme lightweight and performant
