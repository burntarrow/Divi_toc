<?php
/**
 * Divi TOC – Divi 5 Modules Orchestrator
 *
 * This class is required by divi-5/divi5-toc.php
 * and is responsible for:
 *  - Hooking into Divi 5's module dependency tree
 *  - Adding the TableOfContents dependency
 */

namespace Divi_toc\divi-5\Server\Modules;

defined( 'ABSPATH' ) || exit;

use Divi_toc\divi-5\Server\Modules\TableOfContents\TableOfContents;

class Modules {

	/**
	 * Register all Divi 5 server-side dependencies for this plugin.
	 *
	 * Called from divi5-toc.php on 'init'.
	 */
	public static function register() {
		// Make sure the TableOfContents class is loaded.
		$toc_file = __DIR__ . '/TableOfContents/TableOfContents.php';

		if ( file_exists( $toc_file ) ) {
			require_once $toc_file;
		}

		// Hook into Divi 5's module dependency tree. The exact filter name
		// mirrors the pattern used by the working divi-content-toggle plugin.
		add_filter(
			'divi_module_library_modules_dependency_tree',
			[ __CLASS__, 'register_dependencies' ]
		);
	}

	/**
	 * Add our TableOfContents dependency to Divi's dependency tree.
	 *
	 * @param array $dependencies Existing dependencies.
	 *
	 * @return array Modified dependencies including our TOC module.
	 */
	public static function register_dependencies( array $dependencies ): array {
		if ( class_exists( TableOfContents::class ) ) {
			$dependencies[] = new TableOfContents();
		}

		return $dependencies;
	}
}
