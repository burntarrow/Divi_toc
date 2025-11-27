/**
 * Builder entry: registers the Divi TOC module inside the Divi 5 visual builder.
 * Mirrors the wiring used in the Divi 5 example extension: load the module
 * definitions and hand them to the runtime registration API when available.
 */

import extension from './index';
import './components/table-of-contents-module/style.scss';
import './components/table-of-contents-module/module.scss';

const modules = extension?.modules ?? [];

const registerModules = () => {
  const runtime: any = (globalThis as any).divi?.modules;
  const legacy = (globalThis as any).ETBuilderModule;

  // Divi 5: prefer bulk registration if present.
  if (runtime?.registerModules && typeof runtime.registerModules === 'function') {
    runtime.registerModules(modules);
    return;
  }

  // Fallbacks: Divi 4-style APIs usually expect (slug, definition).
  const candidate =
    runtime?.registerModule ||
    runtime?.register ||
    legacy?.registerModule ||
    (globalThis as any).et_pb_register_module;

  if (typeof candidate !== 'function') {
    if (process.env.NODE_ENV !== 'production') {
      // eslint-disable-next-line no-console
      console.warn('[Divi TOC] No module registration API detected on window.divi / ETBuilderModule.');
    }
    return;
  }

  modules.forEach((module: any) => {
    const slug = module?.metadata?.slug || module?.slug || 'divi_toc';
    if (candidate.length >= 2) {
      candidate(slug, module);
    } else {
      candidate(module);
    }
  });
};

registerModules();
