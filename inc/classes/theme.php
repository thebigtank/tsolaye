<?php

class theme {

    /**
     * Registers custom block styles for the theme.
     *
     * This function registers several custom block styles for various core blocks
     * such as 'core/details', 'core/post-terms', 'core/list', 'core/navigation-link',
     * and 'core/heading'. Each style is defined with a unique name, label, and inline
     * CSS styles.
     *
     * Block styles:
     * - Arrow icon for details block
     * - Pill style for post terms block
     * - Checkmark style for list block
     * - Arrow link style for navigation link block
     * - Asterisk style for heading block
     *
     * @return void
     * @author BigTank
     */
    static function block_styles() {
        // Add support for block styles.
        add_theme_support('wp-block-styles');

        // Add support for custom spacing.
        add_theme_support('custom-spacing');
    }

    /**
     * Registers custom block pattern categories for the theme.
     *
     * This function registers a block pattern category named 'tank_page'.
     * This category is used to group block patterns that represent full page layouts.
     *
     * Category details:
     * - Name: tank_page
     * - Label: Pages
     * - Description: A collection of full page layouts.
     *
     * @return void
     * @see https://developer.wordpress.org/reference/functions/register_block_pattern_category/
     * @author BigTank
     */
    static function pattern_categories() {

        register_block_pattern_category(
            'tank_page',
            array(
                'label'       => _x('Pages', 'Block pattern category', 'bigtank'),
                'description' => __('A collection of full page layouts.', 'bigtank'),
            )
        );
    }

    /**
     * Sets the JPEG image quality to the highest level.
     *
     * This function returns a value of 100 to set the JPEG image quality to the maximum level.
     * It can be used to ensure that JPEG images are saved with the best possible quality.
     *
     * @return int JPEG quality value (100).
     * @author BigTank
     */
    static function high_jpg_quality() {
        return 100;
    }
    static function add_type_attribute($tag, $handle) {
        if (in_array($handle, ['vite-client', 'vite-main'])) {
            return str_replace(' src', ' type="module" src', $tag);
        }
        return $tag;
    }

    /**
     * Adds theme support for various WordPress features.
     *
     * This function enables support for the document title tag and post thumbnails.
     * These features allow WordPress to manage the document title and enable the use
     * of featured images in posts and pages.
     *
     * @return void
     * @author BigTank
     */
    static function supports() {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
    }

    /**
     * Registers navigation menus for the theme.
     *
     * This function registers multiple navigation menus, allowing them to be managed
     * from the WordPress admin area. Each menu is given a unique location identifier
     * and a human-readable name.
     *
     * Registered menus:
     * - Action Menu
     * - Main Menu
     * - Footer Menu
     * - Legal Menu
     * - Social Menu
     *
     * @return void
     * @author BigTank
     */
    static function register_nav_menus() {
        register_nav_menus(array(
            'action-menu' => __('Action Menu', 'text_domain'),
            'main-menu' => __('Main Menu', 'text_domain'),
            'footer-menu' => __('Footer Menu', 'text_domain'),
            'legal-menu' => __('Legal Menu', 'text_domain'),
            'social-menu' => __('Social Menu', 'text_domain'),
        ));
    }

    /**
     * Wraps embedded media in a custom div for styling.
     *
     * This function takes the HTML of embedded media (e.g., videos) and wraps it
     * in a div with the class "video-wrapper". This allows for custom styling
     * of embedded media elements.
     *
     * @param string $html The original HTML of the embedded media.
     *
     * @return string Modified HTML wrapped in a div with the class "video-wrapper".
     * @author BigTank
     */
    static function embed_wrapper($html) {
        return '<div class="video-wrapper">' . $html . '</div>';
    }

    /**
     * Modifies the excerpt "more" string.
     *
     * This function changes the string that appears at the end of the excerpt
     * to a simple ellipsis ('...'). This can be useful for customizing the
     * excerpt display on posts.
     *
     * @param string $more The current "more" string.
     *
     * @global WP_Post $post The current post object.
     * @return string The modified "more" string ('...').
     * @author BigTank
     */
    static function change_excerpt($more) {
        global $post;
        return '...';
    }

    /**
     * Adds support for additional MIME types.
     *
     * This function allows the upload of SVG and JSON files by adding their MIME types
     * to the list of allowed MIME types in WordPress. This enables users to upload these
     * file types through the WordPress media uploader.
     *
     * @param array $mimes An array of currently allowed MIME types.
     *
     * @return array The modified array of allowed MIME types with SVG and JSON added.
     * @author BigTank
     */
    static function mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['json'] = 'text/plain';
        return $mimes;
    }

    /**
     * Registers a sidebar for the theme.
     *
     * This function checks if the `register_sidebar` function exists and, if it does,
     * registers a sidebar with the specified settings. The sidebar can be used to add
     * widgets to the sidebar area of the theme.
     *
     * @return void
     * @author BigTank
     */
    static function add_sidebar() {
        if (function_exists('register_sidebar')) {
            register_sidebar([
                'name' => 'Sidebar Widgets',
                'id'   => 'sidebar-widgets',
                'description'   => 'These are widgets for the sidebar.',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>'
            ]);
        }
    }

    static function editor_resources() {
        wp_enqueue_style('gutenberg_styles', get_theme_file_uri('/dist/admin.css'), false, '1.0', 'all');
        wp_enqueue_script('gutenberg_scripts', get_template_directory_uri() . '/dist/app.js', ['wp-blocks', 'wp-element', 'wp-i18n', 'wp-components', 'wp-editor'], '1.0', true);
    }
}
