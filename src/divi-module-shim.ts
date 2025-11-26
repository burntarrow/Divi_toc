// Minimal shim for the Divi 5 module package. This keeps local builds working
// without fetching the private @elegantthemes/module package. At runtime inside
// Divi, the global ETBuilderModule API will exist; the shim attempts to call it
// when available and otherwise no-ops for local development/testing.
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
  const globalRegister =
    (globalThis as any)?.ETBuilderModule?.registerModule ||
    (globalThis as any)?.et_pb_register_module;

  if (typeof globalRegister === 'function') {
    globalRegister(slug, definition);
  } else if (process.env.NODE_ENV !== 'production') {
    // eslint-disable-next-line no-console
    console.warn(
      `[Divi TOC] registerModule shim invoked for "${slug}" but no global Divi register function was found.`,
    );
  }
}
