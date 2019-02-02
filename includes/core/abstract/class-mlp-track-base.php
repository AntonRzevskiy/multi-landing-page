<?php

/**
 * The file that defines the abstract class track for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/abstract
 */

/**
 * Define the track base.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Track_Base implements MLP_Track {


	/**
	 * Unic name in URL & Code.
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $track_id      ID of Track.
	 */
	protected $track_id;

	/**
	 * Track type.
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $type          Type of track.
	 */
	protected $type;

	/**
	 * Post types.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $post_type     Types of posts to which the track belongs.
	 */
	protected $post_type;

	/**
	 * Object type.
	 *
	 * @since      1.0.0
	 *
	 * @var        string         $object_type   Type of object in WP.
	 */
	protected $object_type;

	/**
	 * Required match.
	 *
	 * @since      1.0.0
	 *
	 * @var        bool           $full_match    Regulates the presence of the label in the URL.
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
	 * Initialization.
	 *
	 * @since      1.0.0
	 */
	public function init() {

		/**
		 * Fires after constructor.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( "mlp_init_track_{$this->type}", array( &$this ) );

		/**
		 * Fires after constructor.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          Instance.
		 */
		do_action_ref_array( "mlp_init_track_{$this->track_id}", array( &$this ) );

	}

	/**
	 * Identify the track taxonomy.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a taxonomy.
	 */
	public function is_tax() {
		return $this->type === 'taxonomy';
	}

	/**
	 * Identify the track meta.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a meta.
	 */
	public function is_meta() {
		return $this->type === 'meta';
	}

	/**
	 * Identify the track strict.
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           Is the track a strict.
	 */
	public function is_strict() {
		return (bool) $this->full_match;
	}

	/**
	 * Get property.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $property      Property name.
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
