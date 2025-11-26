<?php
namespace Divi_toc\Modules;

use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModule;

/**
 * Registers Divi 5 modules for this extension.
 */
class Modules {
    public static function register() {
        // Ensure the module class is loaded even when Composer autoloading
        // is unavailable (for example when the plugin zip is installed
        // without running composer install). This mirrors the approach in
        // the Divi 5 example modules so the class exists before registering.
        if ( ! class_exists( TableOfContentsModule::class ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'TableOfContentsModule/TableOfContentsModule.php';
        }

        TableOfContentsModule::register();
    }
}
