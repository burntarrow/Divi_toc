<?php
namespace DiviTOC\Modules\TableOfContents\TableOfContentsTraits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

trait ModuleScriptDataTrait {
    public static function module_script_data( $args ) {
        $elements = $args['elements'];

        $elements->script_data(
            [
                'attrName' => 'module',
            ]
        );
    }
}
