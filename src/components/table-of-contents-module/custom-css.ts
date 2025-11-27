import { ModuleCustomCssDefinition } from '../../divi-module-types';

const CustomCSS: ModuleCustomCssDefinition = {
  toc_container: {
    label: 'TOC Container',
    selector: '.divi-toc-nav',
  },
  toc_item: {
    label: 'TOC Item',
    selector: '.divi-toc-nav li',
  },
  toc_link: {
    label: 'TOC Link',
    selector: '.divi-toc-nav a',
  },
};

export default CustomCSS;
