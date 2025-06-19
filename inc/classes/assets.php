<?php

namespace Tsolaye;

class Assets {
  /**
   * Vite dev server URL
   */
  private static string $devUrl = 'http://localhost:3000';

  /**
   * WP dependencies for Vite-built code
   */
  private static array $deps = [
    'wp-blocks',
    'wp-element',
    'wp-i18n',
    'wp-api-fetch',
  ];

  /**
   * Bootstraps all hooks
   */
  public static function init(): void {
    // Define DIST_DIR / DIST_URI early
    add_action('init',                 [__CLASS__, 'ensureConstants']);

    // Front-end assets
    add_action('wp_enqueue_scripts',   [__CLASS__, 'enqueue']);

    // Block styles (editor + front-end where blocks appear)
    add_action('enqueue_block_assets', [__CLASS__, 'enqueueBlockStyles']);

    // Mark Vite scripts as modules
    add_filter('script_loader_tag',    [__CLASS__, 'addModuleAttribute'], 10, 2);
  }

  /**
   * Define DIST_DIR and DIST_URI if not already set
   */
  public static function ensureConstants(): void {
    if (! defined('DIST_DIR')) {
      define('DIST_DIR', get_theme_file_path('dist'));
    }
    if (! defined('DIST_URI')) {
      define('DIST_URI', get_theme_file_uri('dist'));
    }
  }

  /**
   * Enqueue front-end scripts & styles
   */
  public static function enqueue(): void {
    if (self::isDevServerRunning()) {
      // Dev: HMR client + entry
      wp_enqueue_script('vite-client', self::$devUrl . '/@vite/client', [], null, true);
      wp_enqueue_script('vite-main',   self::$devUrl . '/index.js', self::$deps, null, true);
    } else {
      // Prod: use manifest to pull in hashed CSS/JS
      self::loadFromManifest('index.js', 'theme');
    }

    // Always enqueue fonts & icons
    self::enqueueFonts();
  }

  /**
   * Enqueue fonts and icons
   */
  private static function enqueueFonts(): void {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/0c19da0f18.js');
  }

  /**
   * Enqueue block CSS in both editor and front-end
   */
  public static function enqueueBlockStyles(): void {
    // Always enqueue fonts for block editor
    self::enqueueFonts();

    if (self::isDevServerRunning()) {
      // In dev mode, enqueue HMR client and CSS from dev server
      wp_enqueue_script('vite-client-blocks', self::$devUrl . '/@vite/client', [], null, true);
      wp_enqueue_script('vite-main-blocks', self::$devUrl . '/index.js', self::$deps, null, true);
      wp_enqueue_style('vite-dev-css', self::$devUrl . '/scss/main.scss', [], null);
    } else {
      // Production mode: use manifest
      $manifest  = self::getManifest();
      $entryKey  = 'index.js';
      $cssFiles  = $manifest[$entryKey]['css'] ?? [];

      foreach ($cssFiles as $cssFile) {
        // Clean handle: no slashes
        $handle = 'block-style-' . basename($cssFile);
        wp_enqueue_style(
          $handle,
          DIST_URI . "/{$cssFile}",
          [],
          filemtime(DIST_DIR . "/{$cssFile}"),
          'all'
        );
      }
    }
  }

  /**
   * Pulls CSS/JS from manifest.json for a given entry key
   *
   * @param string $entryKey      e.g. 'src/index.js'
   * @param string $handlePrefix  e.g. 'theme'
   */
  private static function loadFromManifest(string $entryKey, string $handlePrefix): void {
    $manifest = self::getManifest();
    if (empty($manifest[$entryKey])) {
      return;
    }
    $entry = $manifest[$entryKey];

    // CSS first
    foreach ($entry['css'] ?? [] as $cssFile) {
      wp_enqueue_style(
        "{$handlePrefix}-" . basename($cssFile),
        DIST_URI . "/{$cssFile}",
        [],
        filemtime(DIST_DIR . "/{$cssFile}")
      );
    }

    // JS imports (chunks)  
    foreach ($entry['imports'] ?? [] as $importKey) {
      if (! empty($manifest[$importKey])) {
        $chunk = $manifest[$importKey]['file'];
        $handle = "{$handlePrefix}-" . basename($chunk, '.js');
        wp_enqueue_script(
          $handle,
          DIST_URI . "/{$chunk}",
          [],
          filemtime(DIST_DIR . "/{$chunk}"),
          true
        );
      }
    }

    // Main entry script
    if (! empty($entry['file'])) {
      wp_enqueue_script(
        "{$handlePrefix}-main",
        DIST_URI . '/' . $entry['file'],
        self::$deps,
        filemtime(DIST_DIR . '/' . $entry['file']),
        true
      );
    }
  }

  /**
   * Is the Vite dev server up?
   */
  private static function isDevServerRunning(): bool {
    $curl = curl_init(self::$devUrl);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_exec($curl);
    $running = ! curl_errno($curl);
    curl_close($curl);
    return $running;
  }

  /**
   * Add type="module" to identified Vite scripts
   */
  public static function addModuleAttribute(string $tag, string $handle): string {
    // Dev mode scripts (frontend and block editor)
    if (in_array($handle, ['vite-client', 'vite-main', 'vite-client-blocks', 'vite-main-blocks'], true)) {
      return str_replace(' src', ' type="module" src', $tag);
    }

    // Production mode scripts
    if (strpos($handle, 'theme-') === 0) {
      return str_replace(' src', ' type="module" src', $tag);
    }

    return $tag;
  }

  /**
   * Load and decode manifest.json
   */
  private static function getManifest(): array {
    $path = DIST_DIR . '/manifest.json';
    if (! file_exists($path)) {
      return [];
    }
    return json_decode(file_get_contents($path), true) ?: [];
  }
}
