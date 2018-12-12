<?php

/**
 * The file that defines the main class for query results.
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
class MLP_Query extends MLP_Query_Base {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $registry ) {

		$this->registry = $registry;

	}

	/**
	 * .
	 *
	 * @since      1.0.0
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

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $url           .
		 */
		$this->url = apply_filters( 'mlp_url', $this->url );


		$post_type = isset( $this->query_args[ 'post_type' ] ) ? $this->query_args[ 'post_type' ] : 'any';

		$tax_query = isset( $this->query_args[ 'tax_query' ] ) ? $this->query_args[ 'tax_query' ] : array();

		$meta_query = isset( $this->query_args[ 'meta_query' ] ) ? $this->query_args[ 'meta_query' ] : array();


		$registered_tax_tracks = $this->registry->get_taxonomy_tracks( $post_type );

		$white_tax_tracks = $this->get_white_list_tracks( $registered_tax_tracks, $this->url );

		$this->query_args[ 'tax_query' ] = $this->prepare_tax_query( $tax_query, $white_tax_tracks );


		$registered_meta_tracks = $this->registry->get_meta_tracks( $post_type );

		$white_meta_tracks = $this->get_white_list_tracks( $registered_meta_tracks, $this->url );

		$this->query_args[ 'meta_query' ] = $this->prepare_meta_query( $meta_query, $white_meta_tracks );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $query_args    .
		 */
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


}
