<?php
/**
 * Main module definition for Divi TOC.
 *
 * This class uses the Divi 5 module API, mirroring the Divi 4 style while
 * relying on the new registration functions available during the
 * `divi_extensions_init` hook.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Divi_toc_Module' ) ) {

    class Divi_toc_Module extends ET_Builder_Module {
        public $slug       = 'divi_toc';
        public $vb_support = 'on';

        /**
         * Module init sets the name and icon. The icon here follows the
         * Divi 5 example modules convention.
         */
        public function init() {
            $this->name = __( 'Divi TOC', 'divi-toc' );
            $this->icon = ''; // Uses the same icon as in the example modules.
        }

        /**
         * Define settings fields. The fields are later mapped into the front-end
         * script through the render() method via data attributes.
         */
        public function get_fields() {
            $heading_levels = array(
                'h1' => __( 'H1', 'divi-toc' ),
                'h2' => __( 'H2', 'divi-toc' ),
                'h3' => __( 'H3', 'divi-toc' ),
                'h4' => __( 'H4', 'divi-toc' ),
                'h5' => __( 'H5', 'divi-toc' ),
                'h6' => __( 'H6', 'divi-toc' ),
            );

            return array(
                'heading_levels' => array(
                    'label'           => __( 'Heading Levels', 'divi-toc' ),
                    'type'            => 'multiple_checkboxes',
                    'options'         => $heading_levels,
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'description'     => __( 'Choose which heading levels to include in the table of contents.', 'divi-toc' ),
                    'default'         => array( 'h2', 'h3', 'h4', 'h5' ),
                ),
                'include_title' => array(
                    'label'           => __( 'Include Page/Post Title', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'toggle_slug'     => 'main_content',
                    'default'         => 'off',
                    'description'     => __( 'Add the page or post title as the first item.', 'divi-toc' ),
                ),
                'custom_selector' => array(
                    'label'           => __( 'Custom CSS Selector', 'divi-toc' ),
                    'type'            => 'text',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'toggle_slug'     => 'main_content',
                    'description'     => __( 'Optional selector to limit heading scanning. Defaults to #main-content.', 'divi-toc' ),
                ),
                'ignore_classes' => array(
                    'label'           => __( 'Ignore headings with CSS classes', 'divi-toc' ),
                    'type'            => 'text',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'toggle_slug'     => 'main_content',
                    'description'     => __( 'Comma-separated list of classes to skip.', 'divi-toc' ),
                ),
                'minimum_headings' => array(
                    'label'           => __( 'Minimum number of headings required', 'divi-toc' ),
                    'type'            => 'number',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'default'         => '2',
                    'description'     => __( 'If fewer headings are found, the module can hide or show a message.', 'divi-toc' ),
                ),
                'fallback_behavior' => array(
                    'label'           => __( 'When below minimum', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'hide'   => __( 'Hide the module completely', 'divi-toc' ),
                        'show'   => __( 'Show message', 'divi-toc' ),
                    ),
                    'default'         => 'hide',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                ),
                'fallback_message' => array(
                    'label'           => __( 'Fallback message', 'divi-toc' ),
                    'type'            => 'text',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'default'         => __( 'No table of contents available.', 'divi-toc' ),
                ),
                'structure' => array(
                    'label'           => __( 'TOC Structure', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'nested' => __( 'Nested', 'divi-toc' ),
                        'flat'   => __( 'Flat', 'divi-toc' ),
                    ),
                    'default'         => 'nested',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                ),
                'scroll_offset' => array(
                    'label'           => __( 'Scroll offset (px)', 'divi-toc' ),
                    'type'            => 'number',
                    'default'         => '0',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'description'     => __( 'Offset when scrolling to headings. Useful for sticky headers.', 'divi-toc' ),
                ),
                'scrollspy' => array(
                    'label'           => __( 'Enable active section highlighting (scrollspy)', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'on',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                ),
                'style_preset' => array(
                    'label'           => __( 'TOC Style Preset', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'simple'     => __( 'Simple bullet list', 'divi-toc' ),
                        'numbered'   => __( 'Numbered list', 'divi-toc' ),
                        'tree'       => __( 'Indented tree / nested list', 'divi-toc' ),
                        'card'       => __( 'Sidebar card with border/shadow', 'divi-toc' ),
                        'accordion'  => __( 'Collapsible / accordion style', 'divi-toc' ),
                        'floating'   => __( 'Floating box / sticky on scroll', 'divi-toc' ),
                        'boxed'      => __( 'Boxed with background color', 'divi-toc' ),
                        'raw'        => __( 'No styles (raw list)', 'divi-toc' ),
                    ),
                    'default'         => 'simple',
                    'option_category' => 'layout',
                    'tab_slug'        => 'general',
                ),
                'collapsible' => array(
                    'label'           => __( 'Collapsible TOC container', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'general',
                ),
                'start_collapsed' => array(
                    'label'           => __( 'Start collapsed', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'general',
                    'show_if'         => array( 'collapsible' => 'on' ),
                ),
                'collapse_nested' => array(
                    'label'           => __( 'Allow collapsing nested subheadings', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'general',
                ),
                'hide_mobile' => array(
                    'label'           => __( 'Hide TOC on mobile', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'responsive',
                    'tab_slug'        => 'advanced',
                ),
                'hide_tablet' => array(
                    'label'           => __( 'Hide TOC on tablet', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'responsive',
                    'tab_slug'        => 'advanced',
                ),
                'mobile_dropdown' => array(
                    'label'           => __( 'Use dropdown on mobile', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'responsive',
                    'tab_slug'        => 'advanced',
                ),
                'sticky_desktop' => array(
                    'label'           => __( 'Sticky on desktop', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'advanced',
                ),
                'sticky_tablet' => array(
                    'label'           => __( 'Sticky on tablet', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'advanced',
                ),
                'sticky_mobile' => array(
                    'label'           => __( 'Sticky on mobile', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'layout',
                    'tab_slug'        => 'advanced',
                ),
                'back_to_top' => array(
                    'label'           => __( 'Enable Back to top', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                ),
                'back_to_top_mode' => array(
                    'label'           => __( 'Back to top mode', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'section' => __( 'After each section', 'divi-toc' ),
                        'floating' => __( 'Floating button', 'divi-toc' ),
                    ),
                    'default'         => 'floating',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'show_if'         => array( 'back_to_top' => 'on' ),
                ),
                'back_to_top_position' => array(
                    'label'           => __( 'Back to top position', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'bottom-right' => __( 'Bottom right', 'divi-toc' ),
                        'bottom-left'  => __( 'Bottom left', 'divi-toc' ),
                        'top-right'    => __( 'Top right', 'divi-toc' ),
                        'top-left'     => __( 'Top left', 'divi-toc' ),
                    ),
                    'default'         => 'bottom-right',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'general',
                    'show_if'         => array( 'back_to_top' => 'on', 'back_to_top_mode' => 'floating' ),
                ),
                'bullet_style' => array(
                    'label'           => __( 'Bullet / Number Style', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'disc'   => __( 'Disc', 'divi-toc' ),
                        'circle' => __( 'Circle', 'divi-toc' ),
                        'square' => __( 'Square', 'divi-toc' ),
                        'decimal' => __( 'Decimal', 'divi-toc' ),
                        'none'   => __( 'None', 'divi-toc' ),
                    ),
                    'default'         => 'disc',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'design',
                ),
                'indentation' => array(
                    'label'           => __( 'Indentation per level (px)', 'divi-toc' ),
                    'type'            => 'range',
                    'option_category' => 'layout',
                    'tab_slug'        => 'design',
                    'range_settings'  => array(
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 2,
                    ),
                    'default'         => '16',
                ),
                'icon_style' => array(
                    'label'           => __( 'Icon style', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'chevron' => __( 'Chevron', 'divi-toc' ),
                        'dot'     => __( 'Dot', 'divi-toc' ),
                        'plus'    => __( 'Plus/Minus', 'divi-toc' ),
                    ),
                    'default'         => 'chevron',
                    'option_category' => 'configuration',
                    'tab_slug'        => 'design',
                ),
                'active_color' => array(
                    'label'           => __( 'Active item text color', 'divi-toc' ),
                    'type'            => 'color-alpha',
                    'option_category' => 'color',
                    'tab_slug'        => 'design',
                ),
                'active_bg' => array(
                    'label'           => __( 'Active item background', 'divi-toc' ),
                    'type'            => 'color-alpha',
                    'option_category' => 'color',
                    'tab_slug'        => 'design',
                ),
                'active_weight' => array(
                    'label'           => __( 'Active item font weight', 'divi-toc' ),
                    'type'            => 'select',
                    'options'         => array(
                        'normal' => __( 'Normal', 'divi-toc' ),
                        'bold'   => __( 'Bold', 'divi-toc' ),
                    ),
                    'default'         => 'bold',
                    'option_category' => 'font',
                    'tab_slug'        => 'design',
                ),
                'active_underline' => array(
                    'label'           => __( 'Underline active item', 'divi-toc' ),
                    'type'            => 'yes_no_button',
                    'default'         => 'off',
                    'option_category' => 'font',
                    'tab_slug'        => 'design',
                ),
                'active_border_color' => array(
                    'label'           => __( 'Active border color', 'divi-toc' ),
                    'type'            => 'color-alpha',
                    'option_category' => 'color',
                    'tab_slug'        => 'design',
                ),
                'active_border_width' => array(
                    'label'           => __( 'Active border width (px)', 'divi-toc' ),
                    'type'            => 'number',
                    'default'         => '2',
                    'option_category' => 'layout',
                    'tab_slug'        => 'design',
                ),
            );
        }

        /**
         * Render outputs only a placeholder nav. The front-end script scans the
         * document for headings and populates the list client-side. Data settings
         * are passed via a JSON blob for flexibility inside the builder.
         */
        public function render( $attrs, $content = null, $render_slug ) {
            wp_enqueue_style( 'divi_toc_style' );
            wp_enqueue_script( 'divi_toc_frontend' );

            $defaults = array(
                'heading_levels'       => array( 'h2', 'h3', 'h4', 'h5' ),
                'include_title'         => 'off',
                'custom_selector'       => '',
                'ignore_classes'        => '',
                'minimum_headings'      => 2,
                'fallback_behavior'     => 'hide',
                'fallback_message'      => __( 'No table of contents available.', 'divi-toc' ),
                'structure'             => 'nested',
                'scroll_offset'         => 0,
                'scrollspy'             => 'on',
                'style_preset'          => 'simple',
                'collapsible'           => 'off',
                'start_collapsed'       => 'off',
                'collapse_nested'       => 'off',
                'hide_mobile'           => 'off',
                'hide_tablet'           => 'off',
                'mobile_dropdown'       => 'off',
                'sticky_desktop'        => 'off',
                'sticky_tablet'         => 'off',
                'sticky_mobile'         => 'off',
                'back_to_top'           => 'off',
                'back_to_top_mode'      => 'floating',
                'back_to_top_position'  => 'bottom-right',
                'bullet_style'          => 'disc',
                'indentation'           => 16,
                'icon_style'            => 'chevron',
                'active_color'          => '',
                'active_bg'             => '',
                'active_weight'         => 'bold',
                'active_underline'      => 'off',
                'active_border_color'   => '',
                'active_border_width'   => 2,
            );

            $settings = wp_parse_args( $this->props, $defaults );

            /**
             * Allow developers to filter the settings used by the front-end JS.
             */
            $settings = apply_filters( 'divi_toc_settings', $settings, $this );

            $data = esc_attr( wp_json_encode( $settings ) );

            $classes = array( 'divi-toc-wrapper', 'divi-toc-preset-' . esc_attr( $settings['style_preset'] ) );
            if ( 'on' === $settings['collapsible'] ) {
                $classes[] = 'divi-toc-collapsible';
            }
            if ( 'on' === $settings['start_collapsed'] ) {
                $classes[] = 'divi-toc-start-collapsed';
            }
            if ( 'on' === $settings['hide_mobile'] ) {
                $classes[] = 'divi-toc-hide-mobile';
            }
            if ( 'on' === $settings['hide_tablet'] ) {
                $classes[] = 'divi-toc-hide-tablet';
            }

            $html  = '<nav class="divi-toc-nav ' . implode( ' ', array_map( 'sanitize_html_class', $classes ) ) . '" aria-label="' . esc_attr__( 'Table of contents', 'divi-toc' ) . '" data-settings="' . $data . '">';
            $html .= '<div class="divi-toc-header">' . esc_html__( 'Table of Contents', 'divi-toc' );
            $html .= '<button type="button" class="divi-toc-toggle" aria-expanded="true" aria-controls="divi-toc-list">' . esc_html__( 'Toggle', 'divi-toc' ) . '</button>';
            $html .= '</div>';
            $html .= '<div class="divi-toc-body" id="divi-toc-list" data-toc-output></div>';
            $html .= '<div class="divi-toc-message" data-toc-message></div>';
            $html .= '</nav>';

            $html = apply_filters( 'divi_toc_output_html', $html, $settings, $this );

            return $html;
        }
    }

    /**
     * Register the module with Divi.
     */
    function divi_toc_init_module() {
        if ( function_exists( 'et_builder_register_module' ) ) {
            et_builder_register_module( 'Divi_toc_Module' );
        }
    }
    add_action( 'et_builder_ready', 'divi_toc_init_module' );
}

