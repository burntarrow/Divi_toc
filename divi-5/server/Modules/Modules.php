<?php
/**
 * All modules.
 *
 * @package DiviTOC\Modules;
 */

namespace DiviTOC\Modules;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use DiviTOC\Modules\TableOfContents\TableOfContents;

// Register modules with Divi 5's dependency tree.
add_action(
    'divi_module_library_modules_dependency_tree',
    function( $dependency_tree ) {
        $dependency_tree->add_dependency( new TableOfContents() );
    }
);
