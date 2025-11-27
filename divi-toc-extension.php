<?php
/**
 * Divi TOC – Divi 5 Extension Definition
 *
 * This file is required by the `divi.modules.extensions` filter in divi-toc.php.
 * It tells Divi 5 where to find the JS/CSS assets and how to map the PHP
 * module class for front-end rendering.
 */

return [
    // Basic extension metadata.
    'name'        => 'Divi TOC',
    'slug'        => 'divi-toc',
    'version'     => '1.0.2',
    'description' => 'A Divi 5 module that generates a table of contents from page/post headings.',
    'author'      => 'Divi5 Plugins',
    'authorUrl'   => 'https://divi5-plugins.com',
    'textDomain'  => 'divi-toc',

    /**
     * Top-level assets that Divi 5 should load for the extension itself.
     * The `index` script is the compiled version of src/index.ts and mirrors
     * the structure used in the official Divi 5 example modules.
     */
    'assets'      => [
        // Builder assets must be declared at the extension level so Divi 5 can
        // load them before rendering the module picker.
        'scripts' => [
            'builder'  => 'build/divi-toc-builder.js',
            'frontend' => 'build/divi-toc-frontend.js',
        ],
        'styles'  => [
            'builder'  => 'assets/css/divi-toc-builder.css',
            'frontend' => 'assets/css/divi-toc-frontend.css',
        ],
    ],

    /**
     * Modules mapping.
     *
     * Key should be a stable identifier; `slug` should match module.json.
     */
    'modules'     => [
        'TableOfContentsModule' => [
            // PHP class responsible for server-side rendering.
            'phpClass' => '\\Divi_toc\\Modules\\TableOfContentsModule\\TableOfContentsModule',

            // Should match src/components/table-of-contents-module/module.json -> "slug".
            'slug'     => 'divi_toc',

            // Human label used in the builder.
            'name'     => 'Divi TOC',

            // Optional: helps Divi group the module in the builder UI.
            'category' => 'layout',

        ],
    ],
];
