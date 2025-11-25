<?php
namespace Divi_toc\Modules;

use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModule;

/**
 * Registers Divi 5 modules for this extension.
 */
class Modules {
    public static function register() {
        TableOfContentsModule::register();
    }
}
