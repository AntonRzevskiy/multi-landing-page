<?php

/**
 * Fired during plugin deactivation.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Deactivator {


	/**
	 * Deactivation function.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		flush_rewrite_rules();

	}


}
