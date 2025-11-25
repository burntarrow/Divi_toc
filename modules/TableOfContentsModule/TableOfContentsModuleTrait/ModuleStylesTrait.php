<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleStylesTrait {
    public function enqueue_scripts() {
        $base_file = dirname( __DIR__, 2 ) . '/divi-toc.php';
        wp_register_script(
            'divi_toc_frontend',
            plugins_url( 'build/index.js', $base_file ),
            [],
            '1.0.0',
            true
        );
        wp_enqueue_script( 'divi_toc_frontend' );
    }
}
