<?php
namespace DiviTOC\Modules\TableOfContents\TableOfContentsTraits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait ModuleStylesTrait {

    use CustomCssTrait;

    /**
     * Style components for the module.
     */
    public static function module_styles( $args ) {
        $attrs       = $args['attrs'] ?? [];
        $order_class = $args['orderClass'];
        $elements    = $args['elements'];
        $settings    = $args['settings'] ?? [];

        $styles = [
            $elements->style(
                [
                    'attrName'   => 'module',
                    'styleProps' => [
                        'disabledOn' => [
                            'disabledModuleVisibility' => $settings['disabledModuleVisibility'] ?? null,
                        ],
                    ],
                ]
            ),
            CssStyle::style(
                [
                    'selector'  => $order_class,
                    'attr'      => $attrs['css'] ?? [],
                    'cssFields' => self::custom_css(),
                ]
            ),
        ];

        Style::add(
            [
                'id'            => $args['id'],
                'name'          => $args['name'],
                'orderIndex'    => $args['orderIndex'],
                'storeInstance' => $args['storeInstance'],
                'styles'        => $styles,
            ]
        );
    }
}
