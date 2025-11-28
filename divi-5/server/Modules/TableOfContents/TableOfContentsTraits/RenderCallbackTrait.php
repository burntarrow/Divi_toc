<?php
namespace DiviTOC\Modules\TableOfContents\TableOfContentsTraits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use DiviTOC\Modules\TableOfContents\TableOfContents;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\Packages\Module\Module;

trait RenderCallbackTrait {
    public static function render_callback( $attrs, $content, $block, $elements ) {
        $rendered_output = '<nav class="divi-toc-placeholder">Table of Contents placeholder</nav>';

        $parent = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );

        return Module::render(
            [
                'orderIndex'          => $block->parsed_block['orderIndex'],
                'storeInstance'       => $block->parsed_block['storeInstance'],
                'attrs'               => $attrs,
                'elements'            => $elements,
                'id'                  => $block->parsed_block['id'],
                'moduleClassName'     => '',
                'name'                => $block->block_type->name,
                'moduleCategory'      => $block->block_type->category,
                'classnamesFunction'  => [ TableOfContents::class, 'module_classnames' ],
                'stylesComponent'     => [ TableOfContents::class, 'module_styles' ],
                'scriptDataComponent' => [ TableOfContents::class, 'module_script_data' ],
                'parentAttrs'         => $parent->attrs ?? [],
                'parentId'            => $parent->id ?? '',
                'parentName'          => $parent->blockName ?? '',
                'children'            => $elements->style_components( [ 'attrName' => 'module' ] ) . $rendered_output,
            ]
        );
    }
}
