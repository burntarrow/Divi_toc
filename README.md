# Divi TOC

Divi TOC is a Divi 5 extension that adds a Table of Contents module to the Divi Builder. Drop the plugin folder into `wp-content/plugins`, activate it, and the **Divi TOC** module becomes available in the Divi 5 modules list.

Key features:
- Supports H1–H6 selection with defaults for H2–H5.
- Optional page title inclusion and custom content selectors.
- Ignore headings by CSS class, minimum heading threshold with fallback message, and nested or flat list rendering.
- Smooth scrolling with offset, scrollspy highlighting, per-item copy-link buttons, collapsible sections, responsive dropdown mode, sticky presets, and back-to-top helpers.
- Multiple visual presets (simple, numbered, tree, card, accordion, floating, boxed, raw) and design controls for bullets, indentation, icons, and active styles.

## Development

```
npm install
npm run build
npm test
```

Build outputs (Webpack entry points):
- `build/index.js` – Extension manifest bundle (exports modules array used by the builder runtime).
- `build/divi-toc-builder.js` – Divi 5 builder registration bundle.
- `build/divi-toc-frontend.js` – front-end/runtime behaviors.
- `assets/css/index.css` – placeholder stylesheet to satisfy Divi 5 extension asset loading.
- `assets/css/divi-toc-builder.css` – builder styles extracted from the module SCSS.
- `assets/css/divi-toc-frontend.css` – front-end styles extracted from the module SCSS.

### Divi 5 wiring

- `divi-toc.php` registers the extension definition via the `divi.modules.extensions` filter and enqueues front-end assets for live pages.
- `divi-toc-extension.php` mirrors the Divi 5 example extension structure, declaring extension-level builder/front-end assets so the module picker can register the module as soon as the builder iframe loads.
- `src/index.ts` exports the module list consumed by both builder and runtime; `src/builder.tsx` registers modules using the available Divi 5 (or legacy) APIs when `divi-toc-builder.js` loads.

### Legacy Divi 4 code

Legacy PHP for Divi 4 is archived under `legacy/divi4/class-divi-toc-module.php` and is not loaded by the Divi 5 plugin bootstrap. It can be referenced for historical context but is intentionally separated from the Divi 5 wiring.

> Note: if your environment blocks scoped package downloads, configure npm with an accessible registry before running `npm install`.
