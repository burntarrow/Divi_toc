<?php

namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleStylesTrait {
    public function enqueue_scripts() {
        $base_file = dirname( __DIR__, 2 ) . '/divi-toc.php';
        $version   = defined( 'DIVI_TOC_VERSION' ) ? DIVI_TOC_VERSION : '1.0.2';

        wp_register_script(
            'divi-toc-frontend',
            plugins_url( 'build/divi-toc-frontend.js', $base_file ),
            [],
            $version,
            true
        );
        wp_enqueue_script( 'divi-toc-frontend' );

        wp_register_style(
            'divi-toc-frontend',
            plugins_url( 'assets/css/divi-toc-frontend.css', $base_file ),
            [],
            $version
        );
        wp_enqueue_style( 'divi-toc-frontend' );
    }
}
