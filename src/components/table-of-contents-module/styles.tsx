import { ModuleStylesFunction } from '../../divi-module-types';
import { TocAttrs } from './types';

export const Styles: ModuleStylesFunction<TocAttrs> = ({ attrs }) => {
  const indent = attrs.indent ?? 16;
  const listStyle = attrs.list_style ?? 'disc';
  return {
    '.divi-toc-nav ul': {
      listStyleType: listStyle,
    },
    '.divi-toc-nav ul ul': {
      paddingLeft: `${indent}px`,
    },
    '.divi-toc-nav .is-active > a': {
      color: attrs.active_color,
      backgroundColor: attrs.active_background,
      borderColor: attrs.active_border_color,
      fontWeight: attrs.active_font_weight,
      textDecoration: attrs.active_underline ? 'underline' : undefined,
    },
  };
};
