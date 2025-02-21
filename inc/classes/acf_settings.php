<?php

class acf_settings {
    /**
     * Registers custom blocks if ACF is active.
     *
     * This function is dependent on the Advanced Custom Fields (ACF) plugin. If ACF is active,
     * it registers a new block type located in the theme's directory. For more details on registering
     * block types with ACF, refer to the ACF documentation at:
     * https://www.advancedcustomfields.com/resources/acf_register_block_type/
     *
     * For more information on block types in WordPress, see:
     * https://developer.wordpress.org/reference/functions/register_block_type/
     *
     * @since 5.0.0
     * @return void
     */
    static function block_types() {
        if (function_exists('acf_register_block_type')) {
            register_block_type(get_template_directory() . '/blocks/hamburger');
            register_block_type(get_template_directory() . '/blocks/hamburger_close');
        }
    }

    /**
     * Prepends a custom block category to the array of block categories in the block editor.
     *
     * This function adds a new category, 'Custom Blocks', to the beginning of the block categories array
     * if the editor context has a post object. It's useful for organizing custom blocks in the block editor.
     * For more information on modifying block categories in WordPress, refer to:
     * https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
     *
     * @param array $block_categories Existing block categories.
     * @param object $editor_context The editor context, typically containing the post being edited.
     * @return array The modified array of block categories.
     */
    static function block_categories($block_categories, $editor_context) {
        if (!empty($editor_context->post)) {
            array_unshift(
                $block_categories,
                array(
                    'slug'  => 'custom-blocks',
                    'title' => __('Custom Blocks'),
                    'icon'  => null,
                )
            );
        }
        return $block_categories;
    }

    /**
     * Sets the save path for ACF field group JSON files to a theme-specific directory.
     *
     * Modifies the path used by ACF to save its field group JSON files to ensure they are stored within the theme,
     * aiding in version control and deployment practices. For more details on local JSON with ACF, see:
     * https://www.advancedcustomfields.com/resources/local-json/
     *
     * @param string $path The original path for saving ACF JSON files.
     * @return string The new path where ACF JSON files will be saved, within the theme's directory.
     */
    static function json_save_folder($path) {
        // Update path to store ACF JSON files in the theme's 'inc/acf/json' directory.
        $path = get_stylesheet_directory() . '/inc/acf/json/';

        // Return the updated path.
        return $path;
    }


    /**
     * Modifies the paths from which ACF loads its field group JSON files.
     *
     * This function adjusts the loading paths for ACF JSON files, removing the original path and adding a new one
     * that points to the theme's specific directory. This ensures ACF loads JSON field groups from the correct location.
     * For more information on ACF's loading mechanism for JSON files, refer to the ACF documentation:
     * https://www.advancedcustomfields.com/resources/local-json/
     *
     * @param array $paths The original paths array for loading ACF JSON files.
     * @return array The modified paths array with the theme-specific directory appended.
     */
    static function json_load_folder($paths) {
        // Remove the original path (optional).
        unset($paths[0]);

        // Append the new path to the theme's 'inc/acf/json' directory.
        $paths[] = get_stylesheet_directory() . '/inc/acf/json/';

        // Return the updated paths array.
        return $paths;
    }


    /**
     * Adds an ACF options page to the WordPress admin if ACF is active.
     *
     * This function checks if ACF is available and then creates a new options page in the WordPress dashboard
     * where site-wide settings can be managed using ACF fields. This is particularly useful for creating settings
     * that are accessible across the entire site, like global header or footer content.
     * For more information, see: https://www.advancedcustomfields.com/resources/acf_add_options_page/
     *
     * @return void
     */
    static function add_option_page() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title'    => 'Site Options',
                'menu_title'    => 'Site Options',
                'menu_slug'     => 'acf-site-options',
                'capability'    => 'edit_posts',
                'redirect'      => false,
            ));
        }
    }
}
