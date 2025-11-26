<?php
/**
 * Plugin Name: Divi TOC
 * Description: A Divi 5 module that generates a table of contents for page/post headings.
 * Version: 1.0.0
 * Author: Divi5 Plugins
 * Author URI: https://divi5-plugins.com
 * Plugin URI: https://divi5-plugins.com/divi-toc/
 * License: GPLv3 or later
 * Text Domain: divi-toc
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'DIVI_TOC_VERSION' ) ) {
    define( 'DIVI_TOC_VERSION', '1.0.0' );
}

// Load translations.
add_action( 'plugins_loaded', function () {
    load_plugin_textdomain( 'divi-toc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

/**
 * Enqueue the compiled builder/runtime assets so the module registers with Divi 5
 * and front-end behaviors are available in the preview.
 */
function divi_toc_enqueue_assets() {
    $base = __FILE__;
    wp_enqueue_script(
        'divi_toc_bundle',
        plugins_url( 'build/index.js', $base ),
        [],
        DIVI_TOC_VERSION,
        true
    );

    wp_enqueue_style(
        'divi_toc_styles',
        plugins_url( 'assets/css/style.css', $base ),
        [],
        DIVI_TOC_VERSION
    );
}
add_action( 'divi_extensions_enqueue_scripts', 'divi_toc_enqueue_assets' );
add_action( 'wp_enqueue_scripts', 'divi_toc_enqueue_assets' );

// Autoload module classes via Composer when present.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Register Divi 5 modules when the builder is ready.
 */
function divi_toc_register_modules() {
    if ( ! class_exists( '\\ET_Builder_Module' ) ) {
        return;
    }

    require_once __DIR__ . '/modules/Modules.php';
    \Divi_toc\Modules\Modules::register();
}

// Hook into Divi 5 extension bootstrap points.
add_action( 'divi_extensions_init', 'divi_toc_register_modules' );
add_action( 'et_builder_ready', 'divi_toc_register_modules' );
