<?php

/**
 * The file that defines the class related for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/abstract
 */

/**
 * Define the related functionality.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Related_Base {


	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $tracks        Array where value args of track.
	 */
	protected $tracks = array();

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $post_types    Named Array where key = name, value = args.
	 */
	protected $post_types = array();

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_taxonomy_tracks( $post_type = 'any', $object_type = 'post' ) {

		$filtered = array();

		foreach ( $this->tracks as $track ) {

			if ( $track->is_tax() && $object_type === $track->get( 'object_type' ) && ( 'any' === $post_type || in_array( $post_type, $track->get( 'post_type' ) ) ) ) {

				$filtered[] = $track;
			}
		}

		return $filtered;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_meta_tracks( $post_type = 'any', $object_type = 'post' ) {

		$filtered = array();

		foreach ( $this->tracks as $track ) {

			if ( $track->is_meta() && $object_type === $track->get( 'object_type' ) && ( 'any' === $post_type || in_array( $post_type, $track->get( 'post_type' ) ) ) ) {

				$filtered[] = $track;
			}
		}

		return $filtered;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_post_type_names() {

		return array_keys( $this->post_types );
	}


}
