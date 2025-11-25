<?php
namespace Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait;

trait ModuleScriptDataTrait {
    /**
     * Exposes settings to the front-end script.
     */
    public function get_script_data() {
        return [
            'includeTitle'   => 'on' === $this->props['include_title'],
            'headingLevels'  => $this->props['heading_levels'],
            'customSelector' => $this->props['custom_selector'],
            'ignoreClasses'  => $this->props['ignore_classes'],
            'minimum'        => (int) $this->props['minimum_headings'],
            'onEmpty'        => $this->props['on_empty'],
            'emptyMessage'   => $this->props['empty_message'],
            'structure'      => $this->props['structure'],
            'scrollOffset'   => (int) $this->props['scroll_offset'],
            'scrollspy'      => 'on' === $this->props['scrollspy'],
            'preset'         => $this->props['preset'],
            'collapsible'    => 'on' === $this->props['collapsible'],
            'startCollapsed' => 'on' === $this->props['start_collapsed'],
            'collapseChildren'=> 'on' === $this->props['collapse_children'],
            'dropdownMobile' => 'on' === $this->props['dropdown_mobile'],
            'sticky'         => [
                'desktop' => 'on' === $this->props['sticky_desktop'],
                'tablet'  => 'on' === $this->props['sticky_tablet'],
                'mobile'  => 'on' === $this->props['sticky_mobile'],
            ],
            'hide'           => [
                'mobile' => 'on' === $this->props['hide_mobile'],
                'tablet' => 'on' === $this->props['hide_tablet'],
            ],
            'backToTop'      => [
                'enabled'  => 'on' === $this->props['back_to_top'],
                'mode'     => $this->props['back_to_top_mode'],
                'position' => $this->props['back_to_top_position'],
            ],
        ];
    }
}
