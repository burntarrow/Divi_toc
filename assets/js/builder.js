/**
 * Builder integration for Divi TOC.
 *
 * Divi 5 loads this script inside the visual builder so we can provide
 * live previews. The PHP render method already outputs markup and data
 * attributes, so we only need to notify Divi that the module supports
 * live preview and re-run the front-end script after props change.
 */
(function () {
  if (typeof window === 'undefined') {
    return;
  }

  // Listen for Divi builder events to re-run the front-end script when props change.
  document.addEventListener('et_builder_api_ready', function () {
    if (window.ETBuilderBackend && typeof window.ETBuilderBackend.registerModuleData === 'function') {
      window.ETBuilderBackend.registerModuleData('divi_toc', {
        onChange: function () {
          if (window.diviTOCInit) {
            window.diviTOCInit();
          }
        },
      });
    }
  });
})();
