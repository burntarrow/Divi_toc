<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleClassnamesTrait {
    public function module_classnames( $render_slug ) {
        $preset_map = [
            'bullet'      => 'simple',
            'numbered'    => 'numbered',
            'tree'        => 'tree',
            'card'        => 'card',
            'collapsible' => 'accordion',
            'floating'    => 'floating',
            'boxed'       => 'boxed',
            'none'        => 'raw',
        ];

        $preset_key = $preset_map[ $this->props['preset'] ] ?? $this->props['preset'];
        $classes = [
            'divi-toc',
            'divi-toc-preset-' . $preset_key,
        ];

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

        if ( 'on' === $this->props['sticky_desktop'] ) {
            $classes[] = 'divi-toc-sticky-desktop';
        }
        if ( 'on' === $this->props['sticky_tablet'] ) {
            $classes[] = 'divi-toc-sticky-tablet';
        }
        if ( 'on' === $this->props['sticky_mobile'] ) {
            $classes[] = 'divi-toc-sticky-mobile';
        }

        if ( 'on' === $this->props['back_to_top'] && 'floating' === $this->props['back_to_top_mode'] ) {
            $classes[] = 'divi-toc-backtop-enabled';
        }

        return implode( ' ', $classes );
    }
}
