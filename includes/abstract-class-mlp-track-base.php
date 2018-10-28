<?php

/**
 * The file that defines the abstract class track for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track base.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Track_Base {


	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $track_id      .
	 */
	protected $track_id;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $post_type     .
	 */
	protected $post_type;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $type          .
	 */
	protected $type;

	/**
	 * Does the query in the database.
	 *
	 * @since      1.0.0
	 *
	 * @var        bool           $full_match    .
	 */
	protected $full_match;

	/**
	 * Parameters passed to the class constructor.
	 *
	 * In order to implement the following functionality,
	 * must save the original data passed to the constructor.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $args          Parameters of constructor.
	 */
	protected $args;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_tax() {
		return (bool) $this->type === 'taxonomy';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_meta() {
		return (bool) $this->type === 'meta';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_strict() {
		return (bool) $this->full_match;
	}

	/**
	 * Get registred.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $property      .
	 *
	 * @return     mixed          Property of class or null if fail.
	 */
	public function get( $property ) {
		if ( property_exists( $this, $property ) ) {
			return $this->{$property};
		}
		return null;
	}


}
