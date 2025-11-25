<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait RenderCallbackTrait {
    /**
     * Render server-side TOC wrapper; JS enhances with live headings.
     */
    public function render( $attrs, $content = null, $render_slug = '' ) {
        $this->enqueue_scripts();

        $data = apply_filters( 'divi_toc_headings', $this->get_script_data(), $attrs );
        $data_attr = esc_attr( wp_json_encode( $data ) );
        $classnames = esc_attr( $this->module_classnames( $render_slug ) );

        $html  = '<nav class="divi-toc-nav ' . $classnames . '" aria-label="' . esc_attr__( 'Table of contents', 'divi-toc' ) . '" data-divi-toc="' . $data_attr . '">';
        $html .= '<div class="divi-toc__placeholder">' . esc_html__( 'Table of contents will appear here.', 'divi-toc' ) . '</div>';
        $html .= '</nav>';

        /**
         * Filter final TOC markup.
         */
        return apply_filters( 'divi_toc_output_html', $html, $attrs );
    }
}
