<?php

/**
 * The plugin bootstrap file.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 *
 * @wordpress-plugin
 * Plugin Name:       Multi Landing Page
 * Plugin URI:        https://github.com/AntonRzevskiy/multi-landing-page
 * Description:       «MLP» — a magic platform that turns 1 page of the site into multi-page.
 * Version:           1.0.0
 * Author:            Anton Rzhevskiy
 * Author URI:        https://github.com/AntonRzevskiy
 * License:           MIT
 */

// If this file is called directly, abort.
if ( false === defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * It use SemVer - https://semver.org
 */
define( 'MLP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mlp-activator.php
 */
function activate_mlp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mlp-activator.php';
	MLP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mlp-deactivator.php
 */
function deactivate_mlp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mlp-deactivator.php';
	MLP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mlp' );
register_deactivation_hook( __FILE__, 'deactivate_mlp' );

/**
 * The core plugin class.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mlp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( false === function_exists( 'mlp_load' ) ) {

	function mlp_load() {

		$mlp_plugin = new Multi_Landing_Page();
		$mlp_plugin->run();

	}

	/**
	 * Load plugin.
	 */
	mlp_load();

}

/**
 * Global API.
 */
if ( false === function_exists( 'mlp_query' ) ) {

	function mlp_query() {

		global $mlp_query;

		return call_user_func_array( array( $mlp_query, 'query' ), func_get_args() );
	}

}
