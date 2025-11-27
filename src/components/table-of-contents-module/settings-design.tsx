import { ModuleSettingsDefinition } from '../../divi-module-types';
import { TocAttrs } from './types';

const settingsDesign: ModuleSettingsDefinition<TocAttrs> = {
  list_style: {
    type: 'select',
    label: 'List style',
    options: {
      disc: 'Disc',
      circle: 'Circle',
      decimal: 'Numbered',
      none: 'None',
    },
    default: 'disc',
  },
  indent: {
    type: 'range',
    label: 'Indentation per level',
    min: 0,
    max: 48,
    default: 16,
  },
  icon_style: {
    type: 'select',
    label: 'Icon style',
    options: {
      chevron: 'Chevron',
      dot: 'Dot',
      plusminus: 'Plus/Minus',
    },
    default: 'chevron',
  },
  active_color: {
    type: 'color',
    label: 'Active text color',
  },
  active_background: {
    type: 'color',
    label: 'Active background',
  },
  active_border_color: {
    type: 'color',
    label: 'Active border color',
  },
  active_font_weight: {
    type: 'select',
    label: 'Active font weight',
    options: { normal: 'Normal', bold: 'Bold' },
    default: 'bold',
  },
  active_underline: {
    type: 'switch',
    label: 'Underline active item',
    default: false,
  },
};

export default settingsDesign;
