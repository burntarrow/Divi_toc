<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleClassnamesTrait {
    public function module_classnames( $render_slug ) {
        $classes = ['divi-toc', 'preset-' . $this->props['preset']];

        if ( 'on' === $this->props['collapsible'] ) {
            $classes[] = 'divi-toc-collapsible';
        }
        if ( 'on' === $this->props['hide_mobile'] ) {
            $classes[] = 'divi-toc-hide-mobile';
        }
        if ( 'on' === $this->props['hide_tablet'] ) {
            $classes[] = 'divi-toc-hide-tablet';
        }
        if ( 'on' === $this->props['dropdown_mobile'] ) {
            $classes[] = 'divi-toc-dropdown-mobile';
        }

        return implode( ' ', $classes );
    }
}
