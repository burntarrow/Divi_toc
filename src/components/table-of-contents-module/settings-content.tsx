import { ModuleSettingsDefinition } from '../../divi-module-types';
import { TocAttrs } from './types';

const settingsContent: ModuleSettingsDefinition<TocAttrs> = {
  heading_levels: {
    type: 'multiple_checkboxes',
    label: 'Heading Levels',
    description: 'Choose which heading levels to include (default H2-H5).',
    default: ['h2', 'h3', 'h4', 'h5'],
    options: {
      h1: 'H1',
      h2: 'H2',
      h3: 'H3',
      h4: 'H4',
      h5: 'H5',
      h6: 'H6',
    },
  },
  include_title: {
    type: 'switch',
    label: 'Include page/post title as first item',
    default: false,
    description: 'Adds the page title as the first TOC entry and scrolls to top.',
  },
  custom_selector: {
    type: 'text',
    label: 'Custom selector',
    placeholder: '#main-content',
    description: 'Optional CSS selector limiting heading search area.',
  },
  ignore_classes: {
    type: 'text',
    label: 'Ignore headings with CSS classes',
    description: 'Comma separated classes to skip.',
  },
  minimum_headings: {
    type: 'number',
    label: 'Minimum number of headings required',
    default: 2,
  },
  on_empty: {
    type: 'select',
    label: 'When below minimum',
    options: {
      hide: 'Hide the module',
      message: 'Show a message',
    },
    default: 'hide',
  },
  empty_message: {
    type: 'text',
    label: 'Empty message',
    default: 'No table of contents available.',
  },
  structure: {
    type: 'select',
    label: 'TOC structure',
    options: {
      nested: 'Nested',
      flat: 'Flat',
    },
    default: 'nested',
  },
  preset: {
    type: 'select',
    label: 'TOC style preset',
    options: {
      bullet: 'Simple bullet list',
      numbered: 'Numbered list',
      tree: 'Indented tree / nested list',
      card: 'Sidebar card with border/shadow',
      collapsible: 'Collapsible / accordion style',
      floating: 'Floating box / sticky on scroll',
      boxed: 'Boxed with background color',
      none: 'No styles',
    },
    default: 'bullet',
  },
  collapsible: {
    type: 'switch',
    label: 'Collapsible TOC container',
    default: false,
  },
  start_collapsed: {
    type: 'switch',
    label: 'Start collapsed',
    default: false,
  },
  collapse_children: {
    type: 'switch',
    label: 'Allow collapsing nested subheadings',
    default: false,
  },
  scroll_offset: {
    type: 'number',
    label: 'Scroll offset (px)',
    default: 0,
    description: 'Offset scrolling to account for sticky headers.',
  },
  scrollspy: {
    type: 'switch',
    label: 'Enable active section highlighting (scrollspy)',
    default: true,
  },
  dropdown_mobile: {
    type: 'switch',
    label: 'Use dropdown on mobile',
    default: false,
  },
  hide_mobile: {
    type: 'switch',
    label: 'Hide TOC on mobile',
    default: false,
  },
  hide_tablet: {
    type: 'switch',
    label: 'Hide TOC on tablet',
    default: false,
  },
  sticky_desktop: {
    type: 'switch',
    label: 'Sticky on desktop',
    default: false,
  },
  sticky_tablet: {
    type: 'switch',
    label: 'Sticky on tablet',
    default: false,
  },
  sticky_mobile: {
    type: 'switch',
    label: 'Sticky on mobile',
    default: false,
  },
  back_to_top: {
    type: 'switch',
    label: 'Enable Back to top',
    default: false,
  },
  back_to_top_mode: {
    type: 'select',
    label: 'Back to top mode',
    options: {
      section: 'After each section',
      floating: 'Floating button',
    },
    default: 'floating',
  },
  back_to_top_position: {
    type: 'select',
    label: 'Floating button position',
    options: {
      'bottom-right': 'Bottom right',
      'bottom-left': 'Bottom left',
      'top-right': 'Top right',
      'top-left': 'Top left',
    },
    default: 'bottom-right',
  },
};

export default settingsContent;
