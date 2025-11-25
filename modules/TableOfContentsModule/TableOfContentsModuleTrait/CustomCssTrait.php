<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait CustomCssTrait {
    public function custom_css_fields() {
        return [
            'toc_container' => [
                'label'    => __( 'TOC Container', 'divi-toc' ),
                'selector' => '%%order_class%% .divi-toc-nav',
            ],
            'toc_item' => [
                'label'    => __( 'TOC Item', 'divi-toc' ),
                'selector' => '%%order_class%% .divi-toc-nav li',
            ],
            'toc_link' => [
                'label'    => __( 'TOC Link', 'divi-toc' ),
                'selector' => '%%order_class%% .divi-toc-nav a',
            ],
        ];
    }
}
