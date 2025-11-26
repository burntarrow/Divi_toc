import { ModuleSettingsDefinition } from '../../divi-module-shim';
import { TocAttrs } from './types';

const settingsAdvanced: ModuleSettingsDefinition<TocAttrs> = {
  css_id: {
    type: 'text',
    label: 'CSS ID',
  },
  css_class: {
    type: 'text',
    label: 'CSS Class',
  },
};

export default settingsAdvanced;
