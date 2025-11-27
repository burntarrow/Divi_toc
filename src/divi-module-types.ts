export type ModuleSettingsDefinition<TAttrs = Record<string, any>> = Record<string, any> & {
  label?: string;
  slug?: string;
  settings?: Record<string, any>;
};

export type ModuleStylesFunction<TAttrs = Record<string, any>> = (args: {
  attrs: TAttrs;
}) => Record<string, any>;

export type ModuleCustomCssDefinition = Record<string, any>;
