<?php
namespace Divi_toc\Modules\TableOfContentsModule;

use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\CustomCssTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleClassnamesTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleScriptDataTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleStylesTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\RenderCallbackTrait;

/**
 * Divi 5 Table of Contents module definition.
 */
class TableOfContentsModule extends \ET_Builder_Module {
    use CustomCssTrait;
    use ModuleClassnamesTrait;
    use ModuleScriptDataTrait;
    use ModuleStylesTrait;
    use RenderCallbackTrait;

    public $slug       = 'divi-toc';
    public $vb_support = 'on';

    public static function register() {
        new self();
    }

    public function init() {
        $this->name             = __( 'Divi TOC', 'divi-toc' );
        $this->icon_path        = plugin_dir_path( __FILE__ ) . '../../src/icons/table-of-contents-icon';
        $this->folder_name      = 'divi-toc';
        $this->main_css_element = '%%order_class%% .divi-toc-nav';
        $this->settings_modal_toggles = [
            'general' => [
                'toggles' => [
                    'content' => __( 'Content', 'divi-toc' ),
                    'behavior' => __( 'Behavior', 'divi-toc' ),
                ],
            ],
            'advanced' => [
                'toggles' => [
                    'design' => __( 'Design', 'divi-toc' ),
                    'advanced' => __( 'Advanced', 'divi-toc' ),
                ],
            ],
        ];
    }

    /**
     * Module fields mapping to builder controls.
     */
    public function get_fields() {
        return [
            'include_title' => [
                'label'           => __( 'Include page/post title as first item', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'option_category' => 'basic_option',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'content',
                'default'         => 'off',
                'description'     => __( 'Adds the page title as the first TOC entry.', 'divi-toc' ),
            ],
            'heading_levels' => [
                'label'           => __( 'Heading Levels', 'divi-toc' ),
                'type'            => 'multiple_checkboxes',
                'options'         => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'default'         => 'h2|h3|h4|h5',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'content',
                'description'     => __( 'Choose which heading levels to include.', 'divi-toc' ),
            ],
            'custom_selector' => [
                'label'           => __( 'Custom selector', 'divi-toc' ),
                'type'            => 'text',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'content',
                'description'     => __( 'Optional CSS selector to limit heading search.', 'divi-toc' ),
            ],
            'ignore_classes' => [
                'label'           => __( 'Ignore headings with CSS classes', 'divi-toc' ),
                'type'            => 'text',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'content',
                'description'     => __( 'Comma separated list of classes to ignore.', 'divi-toc' ),
            ],
            'minimum_headings' => [
                'label'           => __( 'Minimum number of headings required', 'divi-toc' ),
                'type'            => 'number',
                'default'         => 2,
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'on_empty' => [
                'label'           => __( 'When below minimum', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'hide'   => __( 'Hide the module', 'divi-toc' ),
                    'message'=> __( 'Show a message', 'divi-toc' ),
                ],
                'default'         => 'hide',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'empty_message' => [
                'label'           => __( 'Empty message', 'divi-toc' ),
                'type'            => 'text',
                'default'         => __( 'No table of contents available.', 'divi-toc' ),
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'structure' => [
                'label'           => __( 'TOC structure', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'nested' => __( 'Nested', 'divi-toc' ),
                    'flat'   => __( 'Flat', 'divi-toc' ),
                ],
                'default'         => 'nested',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'scroll_offset' => [
                'label'           => __( 'Scroll offset (px)', 'divi-toc' ),
                'type'            => 'number',
                'default'         => 0,
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'scrollspy' => [
                'label'           => __( 'Enable active section highlighting (scrollspy)', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'on',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'preset' => [
                'label'           => __( 'TOC style preset', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'bullet'     => __( 'Simple bullet list', 'divi-toc' ),
                    'numbered'   => __( 'Numbered list', 'divi-toc' ),
                    'tree'       => __( 'Indented tree / nested list', 'divi-toc' ),
                    'card'       => __( 'Sidebar card with border/shadow', 'divi-toc' ),
                    'collapsible'=> __( 'Collapsible / accordion style', 'divi-toc' ),
                    'floating'   => __( 'Floating box / sticky on scroll', 'divi-toc' ),
                    'boxed'      => __( 'Boxed with background color', 'divi-toc' ),
                    'none'       => __( 'No styles', 'divi-toc' ),
                ],
                'default'         => 'bullet',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'collapsible' => [
                'label'           => __( 'Collapsible TOC container', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'start_collapsed' => [
                'label'           => __( 'Start collapsed', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'collapse_children' => [
                'label'           => __( 'Allow collapsing nested subheadings', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'hide_mobile' => [
                'label'           => __( 'Hide TOC on mobile', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'hide_tablet' => [
                'label'           => __( 'Hide TOC on tablet', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'dropdown_mobile' => [
                'label'           => __( 'Use dropdown on mobile', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'sticky_desktop' => [
                'label'           => __( 'Sticky on desktop', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'sticky_tablet' => [
                'label'           => __( 'Sticky on tablet', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'sticky_mobile' => [
                'label'           => __( 'Sticky on mobile', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'back_to_top' => [
                'label'           => __( 'Enable Back to top', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'back_to_top_mode' => [
                'label'           => __( 'Back to top mode', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'section' => __( 'After each section', 'divi-toc' ),
                    'floating' => __( 'Floating button', 'divi-toc' ),
                ],
                'default'         => 'floating',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'back_to_top_position' => [
                'label'           => __( 'Floating button position', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'bottom-right' => __( 'Bottom right', 'divi-toc' ),
                    'bottom-left'  => __( 'Bottom left', 'divi-toc' ),
                    'top-right'    => __( 'Top right', 'divi-toc' ),
                    'top-left'     => __( 'Top left', 'divi-toc' ),
                ],
                'default'         => 'bottom-right',
                'tab_slug'        => 'general',
                'toggle_slug'     => 'behavior',
            ],
            'list_style' => [
                'label'           => __( 'List style', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'disc'    => __( 'Disc', 'divi-toc' ),
                    'circle'  => __( 'Circle', 'divi-toc' ),
                    'decimal' => __( 'Numbered', 'divi-toc' ),
                    'none'    => __( 'None', 'divi-toc' ),
                ],
                'default'         => 'disc',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'indent' => [
                'label'           => __( 'Indentation per level', 'divi-toc' ),
                'type'            => 'range',
                'default'         => 16,
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 64,
                    'step' => 1,
                ],
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'icon_style' => [
                'label'           => __( 'Icon style', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'chevron'   => __( 'Chevron', 'divi-toc' ),
                    'dot'       => __( 'Dot', 'divi-toc' ),
                    'plusminus' => __( 'Plus/Minus', 'divi-toc' ),
                ],
                'default'         => 'chevron',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'active_color' => [
                'label'           => __( 'Active text color', 'divi-toc' ),
                'type'            => 'color-alpha',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'active_background' => [
                'label'           => __( 'Active background', 'divi-toc' ),
                'type'            => 'color-alpha',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'active_border_color' => [
                'label'           => __( 'Active border color', 'divi-toc' ),
                'type'            => 'color-alpha',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'active_font_weight' => [
                'label'           => __( 'Active font weight', 'divi-toc' ),
                'type'            => 'select',
                'options'         => [
                    'normal' => __( 'Normal', 'divi-toc' ),
                    'bold'   => __( 'Bold', 'divi-toc' ),
                ],
                'default'         => 'bold',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
            'active_underline' => [
                'label'           => __( 'Underline active item', 'divi-toc' ),
                'type'            => 'yes_no_button',
                'default'         => 'off',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'design',
            ],
        ];
    }
}
