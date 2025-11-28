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

define( 'DIVI_TOC_MODULES_URL', DIVI_TOC_PLUGIN_URL . 'divi-5/visual-builder/src/modules' );
define( 'DIVI_TOC_MODULES_PATH', DIVI_TOC_PLUGIN_DIR . 'divi-5/visual-builder/src/modules' );

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
 * Front-end assets (for smooth scroll behavior, etc.).
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

// Load Divi 5 modules.
require_once DIVI_TOC_PLUGIN_DIR . 'divi-5/divi5-toc.php';
