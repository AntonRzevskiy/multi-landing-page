<?php

/**
 * The file that defines the interface track for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/interface
 */

/**
 * Define the interface track.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
interface MLP_Track {


	/**
	 * Initialization object.
	 *
	 * The first function after the class constructor.
	 * Used to expand the class.
	 *
	 * @since      1.0.0
	 */
	public function init();

	/**
	 * Identify the track taxonomy.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a taxonomy.
	 */
	public function is_tax();

	/**
	 * Identify the track meta.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a meta.
	 */
	public function is_meta();

	/**
	 * Identify the track strict.
	 *
	 * Determines whether the presence of the track in the URL is mandatory.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a strict.
	 */
	public function is_strict();

	/**
	 * Get property.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $property      Property name.
	 *
	 * @return     mixed          Property of class or null if fail.
	 */
	public function get( string $property );


}
