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
	 * Tracks.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $tracks        Collection of registered tracks.
	 */
	protected $tracks = array();

	/**
	 * Post types.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $post_types    Named Array where key = name, value = args.
	 */
	protected $post_types = array();

	/**
	 * Get taxonomy tracks.
	 *
	 * @since      1.0.0
	 *
	 * @param      string        $post_type      Optional. Post type. Default any.
	 * @param      string        $object_type    Optional. WP object type. Default post.
	 *
	 * @return     array         Slice the tracks by parameters.
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
	 * Get meta tracks.
	 *
	 * @since      1.0.0
	 *
	 * @param      string        $post_type      Optional. Post type. Default any.
	 * @param      string        $object_type    Optional. WP object type. Default post.
	 *
	 * @return     array         Slice the tracks by parameters.
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
	 * Get names of registered post-types.
	 *
	 * @since      1.0.0
	 *
	 * @return     array         Names of post-types.
	 */
	public function get_post_type_names() {

		return array_keys( $this->post_types );
	}


}
