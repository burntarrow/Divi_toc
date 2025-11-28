<?php
// Load Divi 5 modules.
require_once DIVI_TOC_PLUGIN_DIR . 'divi-5/vendor/autoload.php';
require_once DIVI_TOC_PLUGIN_DIR . 'divi-5/server/Modules/Modules.php';

/**
 * Enqueue Divi 5 Visual Builder Assets
 *
 * @since 1.0.0
 */
function divi_toc_divi5_enqueue_visual_builder_assets() {
    if ( et_core_is_fb_enabled() && et_builder_d5_enabled() ) {
        wp_enqueue_script(
            'divi-toc-divi5-visual-builder',
            DIVI_TOC_PLUGIN_URL . 'divi-5/visual-builder/build/build.js',
            array( 'divi-vendor-react', 'jquery', 'divi-module-library', 'wp-hooks', 'divi-rest' ),
            DIVI_TOC_VERSION,
            true
        );
    }
}
add_action( 'divi_visual_builder_assets_before_enqueue_scripts', 'divi_toc_divi5_enqueue_visual_builder_assets' );

// Load block wise scripts.
add_filter( 'separate_core_block_assets', '__return_true' );
add_filter( 'should_load_separate_core_block_assets', '__return_true' );
