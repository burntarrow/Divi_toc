// src/index.ts
/**
 * Divi TOC – Root Extension Index
 *
 * - Imports the TableOfContentsModule definition from the module folder
 * - Registers it with the Divi 5 builder (via a global registry)
 * - Exports the modules collection for potential future use
 */

import { TableOfContentsModule } from './components/table-of-contents-module';

declare const window: any;

// Register the module with Divi 5's builder runtime, if available.
// Adjust this API call if your Divi 5 dev docs specify a different global.
if (typeof window !== 'undefined' && window.etBuilderExtensions) {
  const slug =
    TableOfContentsModule?.metadata?.slug ||
    'divi_toc';

  window.etBuilderExtensions.registerModule(slug, TableOfContentsModule);
}

// Export a modules collection (useful if Divi or tooling expects it)
export const modules = [TableOfContentsModule];

export default {
  modules,
};
