<?php

/**
 * The file that defines the class for display filter tax in admin panel.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the filter tax in admin panel.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Tax_Filter {


	/**
	 * Tracks registered like filtered.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $filter_track  .
	 */
	protected $filter_track = array();

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		add_action( 'mlp_init_track_taxonomy', array( $this, 'define_admin_filter' ) );

		add_action( 'restrict_manage_posts', array( $this, 'add_admin_filter_posts' ) );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function define_admin_filter( $track ) {

		$args = $track->get( 'args' );

		$track_id = $track->get( 'track_id' );

		if ( false === isset( $args[ 'filter' ] ) ) {

			return;
		}

		if ( false === $args[ 'filter' ] ) {

			return;
		}

		if ( false === isset( $args[ 'filter_args' ] ) ) {

			$args[ 'filter_args' ] = array();
		}

		$args[ 'filter_args' ] = wp_parse_args( $args[ 'filter_args' ], array(

			'show_option_none'  => __( 'All' ),
			'option_none_value' => '',
			'show_count'        => true,
			'hide_empty'        => true,
			'echo'              => true,
			'name'              => $track_id,
			'taxonomy'          => $track_id,
			'hide_if_empty'     => true,
			'value_field'       => 'name',

		) );

		$this->filter_track[ $args[ 'object_type' ] ][ $track_id ] = array( 'args' => $args[ 'filter_args' ], 'post_type' => $track->get( 'post_type' ) );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function add_admin_filter_posts( $post_type ) {

		if ( empty( $this->filter_track ) ) {

			return;
		}

		if ( false === isset( $this->filter_track[ 'post' ] ) ) {
			
			return;
		}

		foreach ( $this->filter_track[ 'post' ] as $id => $filter ) {

			if ( empty( $filter[ 'post_type' ] ) || in_array( $post_type, $filter[ 'post_type' ] ) ) {

				$filter[ 'args' ][ 'selected' ] = ( isset( $_GET[ $id ] ) ? $_GET[ $id ] : -1 );

				wp_dropdown_categories( $filter[ 'args' ] );

			}

		}

	}


}
