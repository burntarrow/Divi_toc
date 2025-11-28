<?php
/**
 * Table of Contents module dependency.
 *
 * @package DiviTOC\Modules\TableOfContents
 */

namespace DiviTOC\Modules\TableOfContents;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use DiviTOC\Modules\TableOfContents\TableOfContentsTraits;
use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

class TableOfContents implements DependencyInterface {

    use TableOfContentsTraits\RenderCallbackTrait;
    use TableOfContentsTraits\ModuleClassnamesTrait;
    use TableOfContentsTraits\ModuleStylesTrait;
    use TableOfContentsTraits\ModuleScriptDataTrait;

    /**
     * Register the module with Divi 5.
     */
    public function load() {
        $module_json_folder_path = dirname( __DIR__, 3 ) . '/visual-builder/src/modules/table-of-contents';

        add_action(
            'init',
            function() use ( $module_json_folder_path ) {
                ModuleRegistration::register_module(
                    $module_json_folder_path,
                    [ 'render_callback' => [ TableOfContents::class, 'render_callback' ] ]
                );
            }
        );
    }
}
