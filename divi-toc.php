<?php
/**
 * Plugin Name:       Divi TOC
 * Description:       A Divi 5 module that generates a table of contents for page/post headings.
 * Version:           1.0.2
 * Author:            Divi5 Plugins
 * Author URI:        https://divi5-plugins.com
 * Plugin URI:        https://divi5-plugins.com/divi-toc/
 * License:           GPL-3.0-or-later
 * Text Domain:       divi-toc
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DIVI_TOC_PLUGIN_FILE', __FILE__ );
define( 'DIVI_TOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DIVI_TOC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DIVI_TOC_VERSION', '1.0.2' );

/**
 * Load plugin text domain.
 */
add_action( 'plugins_loaded', function () {
    load_plugin_textdomain(
        'divi-toc',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
} );

/**
 * Load the Modules class directly (no Composer required).
 */
require_once DIVI_TOC_PLUGIN_DIR . 'modules/Modules.php';

/**
 * Register Divi 5 modules on init (PHP side).
 */
add_action( 'init', function () {
    if ( class_exists( '\Divi_toc\Modules\Modules' ) ) {
        \Divi_toc\Modules\Modules::register();
    }
} );

/**
 * Register this plugin as a Divi 5 Extension.
 * Divi 5 will read divi-toc-extension.php and load our JS + CSS.
 */
add_filter( 'divi.modules.extensions', function ( $extensions ) {
    $extensions['divi-toc'] = require __DIR__ . '/divi-toc-extension.php';
    return $extensions;
} );

/**
 * Front-end assets (for smooth scroll behavior, etc.).
 * This is separate from the builder JS that Divi enqueues via the extension.
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'divi-toc-frontend',
        DIVI_TOC_PLUGIN_URL . 'build/divi-toc-frontend.js',
        [],
        DIVI_TOC_VERSION,
        true
    );

    wp_enqueue_style(
        'divi-toc-frontend',
        DIVI_TOC_PLUGIN_URL . 'assets/css/divi-toc-frontend.css',
        [],
        DIVI_TOC_VERSION
    );
} );
