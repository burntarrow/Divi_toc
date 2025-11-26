import metadata from './module.json';
import Edit from './edit';
import settingsContent from './settings-content';
import settingsDesign from './settings-design';
import settingsAdvanced from './settings-advanced';
import CustomCSS from './custom-css';
import { Styles } from './styles';
import icon from '../../module-icons';

export default {
  metadata,
  settings: {
    content: settingsContent,
    design: settingsDesign,
    advanced: settingsAdvanced,
  },
  edit: Edit,
  styles: Styles,
  customCss: CustomCSS,
  icon,
};
