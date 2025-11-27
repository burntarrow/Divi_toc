<?php
/**
 * Divi 5 Extension Descriptor for Divi TOC
 *
 * This tells Divi 5 that this plugin contains a Divi 5 extension,
 * and where its assets, modules, and manifest are located.
 */

defined( 'ABSPATH' ) || exit;

return [
    'name'        => 'Divi TOC',
    'slug'        => 'divi-toc',
    'description' => 'Table of Contents module for Divi 5.',
    'version'     => '1.0.0',

    // REQUIRED: folder where your built assets live
    'assets_path' => plugin_dir_path( __FILE__ ) . 'build/',
    'assets_url'  => plugin_dir_url( __FILE__ ) . 'build/',

    // REQUIRED: where your module.json definitions live
    'modules_path' => plugin_dir_path( __FILE__ ) . 'src/components/',
];
