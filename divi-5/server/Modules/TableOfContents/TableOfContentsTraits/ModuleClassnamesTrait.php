<?php
namespace DiviTOC\Modules\TableOfContents\TableOfContentsTraits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Options\Element\ElementClassnames;

trait ModuleClassnamesTrait {
    public static function module_classnames( $args ) {
        $classnames_instance = $args['classnamesInstance'];
        $attrs               = $args['attrs'];

        $classnames_instance->add(
            ElementClassnames::classnames(
                [
                    'attrs' => array_merge(
                        $attrs['module']['decoration'] ?? [],
                        [
                            'link' => $attrs['module']['advanced']['link'] ?? [],
                        ]
                    ),
                ]
            )
        );
    }
}
