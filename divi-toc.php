<?php
/**
 * Plugin Name:       Divi TOC
 * Description:       A Divi 5 module that generates a table of contents for page/post headings.
 * Version:           1.0.0
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
 * Register Divi 5 modules on init.
 */
add_action( 'init', function () {
    if ( class_exists( '\Divi_toc\Modules\Modules' ) ) {
        \Divi_toc\Modules\Modules::register();
    }
} );

/**
 * Enqueue front-end assets.
 */
add_action( 'wp_enqueue_scripts', function () {
    // Front-end JS bundle built by webpack
    wp_enqueue_script(
        'divi-toc-frontend',
        DIVI_TOC_PLUGIN_URL . 'build/divi-toc-frontend.js',
        [],
        '1.0.0',
        true
    );

    // Shared styles
    wp_enqueue_style(
        'divi-toc-style',
        DIVI_TOC_PLUGIN_URL . 'assets/css/divi-toc.css',
        [],
        '1.0.0'
    );
} );

/**
 * Enqueue builder-only assets for Divi 5.
 * Adjust the hook if Elegant Themes recommends something else for Divi 5.
 */
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'divi-toc-builder',
        DIVI_TOC_PLUGIN_URL . 'build/divi-toc-builder.js',
        [ 'react', 'react-dom' ],
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'divi-toc-style',
        DIVI_TOC_PLUGIN_URL . 'assets/css/divi-toc.css',
        [],
        '1.0.0'
    );
} );
