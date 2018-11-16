<?php

/**
 * Fired during plugin activation.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Activator {


	/**
	 * Activation function.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		flush_rewrite_rules();

	}


}
