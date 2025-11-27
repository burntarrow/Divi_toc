// Minimal shim for Divi 5 / Divi 4 module registration.
//
// In Divi 5, the official example modules call into @elegantthemes/module,
// which in turn talks to the runtime. Since we don’t have that private
// package here, we use a shim that looks for likely globals.
//
// - In Divi 5:   window.divi?.modules?.register
// - Legacy Divi: window.ETBuilderModule?.registerModule or window.et_pb_register_module

export type ModuleSettingsDefinition<TAttrs = Record<string, any>> = Record<string, any> & {
  label?: string;
  slug?: string;
  settings?: Record<string, any>;
};

export type ModuleStylesFunction<TAttrs = Record<string, any>> = (args: {
  attrs: TAttrs;
}) => Record<string, any>;

export type ModuleCustomCssDefinition = Record<string, any>;

export function registerModule(
  slug: string,
  definition: Record<string, any>,
): void {
  const g: any = globalThis as any;

  const candidates = [
    // Divi 5-style runtime (probable shape)
    g?.divi?.modules?.register,
    // Legacy/newish ET API
    g?.ETBuilderModule?.registerModule,
    // Very old API
    g?.et_pb_register_module,
  ];

  const fn = candidates.find((f) => typeof f === 'function');

  if (fn) {
    fn(slug, definition);
  } else if (process.env.NODE_ENV !== 'production') {
    // eslint-disable-next-line no-console
    console.warn(
      `[Divi TOC] registerModule shim invoked for "${slug}" but no known Divi module registration API was found on window.divi / ETBuilderModule / et_pb_register_module.`,
    );
  }
}
