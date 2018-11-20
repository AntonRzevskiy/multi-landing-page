<?php

/**
 * The file that defines the abstract class for query results.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the query functionality.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
abstract class MLP_Query_Base {


	/**
	 * The tracks registered via hooks.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        MLP_Registry   $registry      The instance of class.
	 */
	protected $registry;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        object         $last_query    .
	 */
	protected $last_query;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        array          $query_args    .
	 */
	protected $query_args;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        array          $url           .
	 */
	protected $url;

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @var        bool           $strict        .
	 */
	protected $strict;

	/**
	 * .
	 *
	 * @since    1.0.0
	 *
	 * @param      bool           $strict        Optional. TRUE - This means that the result of the query
	 *                                           will not be processed additionally. FALSE - Postprocessing
	 *                                           is assumed. Also, all tracks get information about this value.
	 *                                           Default true.
	 */
	public function query( $query_args = array(), $url = array(), $strict = true ) {

		$this->strict = $strict;

		$this->query_args = wp_parse_args( $query_args );

		// parse URL
		$this->url = wp_parse_args( $url, $_GET );


		$post_type = isset( $this->query_args[ 'post_type' ] ) ? $this->query_args[ 'post_type' ] : 'any';


		$registered_tax_tracks = $this->registry->get_taxonomy_tracks( $post_type );

		$white_tax_tracks = $this->get_white_list_tracks( $registered_tax_tracks, $this->url );

		$this->query_args[ 'tax_query' ] = $this->prepare_tax_query( $white_tax_tracks );


		$registered_meta_tracks = $this->registry->get_meta_tracks( $post_type );

		$white_meta_tracks = $this->get_white_list_tracks( $registered_meta_tracks, $this->url );

		$this->query_args[ 'meta_query' ] = $this->prepare_meta_query( $white_meta_tracks );


		$this->query_args = apply_filters( 'mlp_query', $this->query_args );

		$this->last_query = new WP_Query( $this->query_args );


		if ( false === $this->strict ) {

			/**
			 * .
			 *
			 * @since      1.0.0
			 *
			 * @param      object         $this          .
			 */
			do_action_ref_array( 'mlp_query_post_processing', array( &$this ) );

		}


		return $this->last_query;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 */
	protected function prepare_tax_query( $args = array() ) {

		$tax_query = array();

		foreach ( $args as $params ) {

			$fn = $params[ 'track' ]->get( 'fill_tax_query' );

			$part = call_user_func_array( $fn, $params );

			if ( $part ) {

				$tax_query[] = $part;

			}

		}

		if ( 1 < count( $tax_query ) ) $tax_query[ 'relation' ] = 'AND';

		return $tax_query;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 */
	protected function prepare_meta_query( $args = array() ) {

		$meta_query = array();

		foreach ( $args as $params ) {

			$fn = $params[ 'track' ]->get( 'fill_meta_query' );

			$part = call_user_func_array( $fn, $params );

			if ( $part ) {

				$meta_query[] = $part;

			}

		}

		if ( 1 < count( $meta_query ) ) $meta_query[ 'relation' ] = 'AND';

		return $meta_query;
	}

	/**
	 * Get allowed tracks.
	 *
	 * @since      1.0.0
	 *
	 * @access     protected
	 *
	 * @return     array          Result. ( Track :: url )
	 */
	protected function get_white_list_tracks( $tracks, $url ) {

		$url = (array) $url;

		$out = array();

		foreach ( $tracks as $track ) {

			if ( array_key_exists( $track->get( 'track_id' ), $url ) ) {

				$out[] = array( 'url' => $url[ $track->get( 'track_id' ) ], 'track' => $track, 'strict' => $this->strict );

			} else {

				$out[] = array( 'url' => '', 'track' => $track, 'strict' => $this->strict );

			}
		}

		return $out;
	}


}
