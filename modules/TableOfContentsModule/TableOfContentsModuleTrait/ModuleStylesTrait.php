<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleStylesTrait {
    public function enqueue_scripts() {
        $base_file = dirname( __DIR__, 2 ) . '/divi-toc.php';
        $version   = defined( 'DIVI_TOC_VERSION' ) ? DIVI_TOC_VERSION : '1.0.0';
        wp_register_script(
            'divi_toc_frontend',
            plugins_url( 'build/index.js', $base_file ),
            [],
            $version,
            true
        );
        wp_enqueue_script( 'divi_toc_frontend' );

        wp_register_style(
            'divi_toc_frontend',
            plugins_url( 'assets/css/style.css', $base_file ),
            [],
            $version
        );
        wp_enqueue_style( 'divi_toc_frontend' );
    }
}
