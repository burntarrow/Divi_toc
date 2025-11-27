<?php
/**
 * Front-end PHP class for the Divi TOC module (Divi 5).
 *
 * This does NOT extend ET_Builder_Module (Divi 4 base class).
 * It just wires traits and script/style handles for Divi 5.
 */

namespace Divi_toc\Modules\TableOfContentsModule;

defined( 'ABSPATH' ) || exit;

// Manually require trait files (no Composer autoload here).
require_once __DIR__ . '/TableOfContentsModuleTrait/CustomCssTrait.php';
require_once __DIR__ . '/TableOfContentsModuleTrait/ModuleClassnamesTrait.php';
require_once __DIR__ . '/TableOfContentsModuleTrait/ModuleScriptDataTrait.php';
require_once __DIR__ . '/TableOfContentsModuleTrait/ModuleStylesTrait.php';
// If you have RenderCallbackTrait and still use it, uncomment this:
// require_once __DIR__ . '/TableOfContentsModuleTrait/RenderCallbackTrait.php';

use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\CustomCssTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleClassnamesTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleScriptDataTrait;
use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\ModuleStylesTrait;
// use Divi_toc\Modules\TableOfContentsModule\TableOfContentsModuleTrait\RenderCallbackTrait;

class TableOfContentsModule {

	use CustomCssTrait;
	use ModuleClassnamesTrait;
	use ModuleScriptDataTrait;
	use ModuleStylesTrait;
	// use RenderCallbackTrait;

	/**
	 * Module slug – MUST match the slug in your module.json
	 * and the JS side.
	 */
	public static function get_slug() {
		return 'divi_toc';
	}

	/**
	 * Human-readable name.
	 */
	public static function get_name() {
		return __( 'Divi TOC', 'divi-toc' );
	}

	/**
	 * Divi 5’s front-end rendering is handled by JS (builder + frontend
	 * bundles), so we don’t output HTML here.
	 *
	 * If in future you want server-side HTML, you can implement it here.
	 */
	public static function render( array $props = [], array $children = [] ) {
		// No-op – JS handles everything.
		return '';
	}

	/**
	 * Script handles used for front-end rendering.
	 * MUST match what you register/enqueue in divi-toc.php.
	 */
	public static function get_script_handles() {
		return array(
			'divi-toc-frontend',
		);
	}

	/**
	 * Style handles for this module (if any).
	 * Keep this in sync with divi-toc.php's wp_enqueue_style handle.
	 */
        public static function get_style_handles() {
                return array(
                        'divi-toc-frontend',
                );
        }
}
