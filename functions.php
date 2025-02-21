<?php

// =========================================================================
// AUTOLOADER
// =========================================================================
require_once __DIR__ . '/inc/autoloader.php';

// =========================================================================
// HELPER FUNCTIONS
// =========================================================================
require_once __DIR__ . '/inc/helpers.php';

// =========================================================================
// WORDPRESS HOOKS
// =========================================================================
require_once __DIR__ . '/inc/hooks.php';

// =========================================================================
// CUSTOM BINDINGS
// =========================================================================
// add_action('init', 'bigtank_patterns_register_meta');

// function bigtank_patterns_register_meta() {
//   register_meta(
//     'post',
//     'bigtank_patterns_mood',
//     array(
//       'show_in_rest'      => true,
//       'single'            => true,
//       'type'              => 'string',
//       'sanitize_callback' => 'wp_strip_all_tags'
//     )
//   );

//   register_meta(
//     'post',
//     'bigtank_patterns_image_url',
//     array(
//       'show_in_rest'      => true,
//       'single'            => true,
//       'type'              => 'string',
//       'sanitize_callback' => 'esc_url_raw',
//       'default'           => get_theme_file_uri('assets/image/code.png')
//     )
//   );

//   register_meta(
//     'post',
//     'bigtank_patterns_image_alt',
//     array(
//       'show_in_rest'      => true,
//       'single'            => true,
//       'type'              => 'string',
//       'sanitize_callback' => 'wp_strip_all_tags'
//     )
//   );
// }
