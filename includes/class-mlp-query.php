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
 * Define the query public functionality.
 *
 *
 * @since      1.0.0
 *
 * @see        MLP_Query_Base
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Query extends MLP_Query_Base {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {
		
	}

	/**
	 * Execute database query via class WP_Query.
	 *
	 * @since      1.0.0
	 *
	 * @param      string|array   $query_args    Optional. The same arguments as the class WP_Query.
	 *                                           Default empty array.
	 *
	 * @param      string|array   $url           Optional. The default arguments for tracks.
	 *                                           Which will be replaced from the array $_GET.
	 *                                           Default empty array.
	 *
	 * @param      bool           $strict        Optional. TRUE - This means that the result of the query
	 *                                           will not be processed additionally. FALSE - Postprocessing
	 *                                           is assumed. Also, all tracks get information about this value.
	 *                                           Default true.
	 *
	 * @return     array          The result of a query database.
	 */
	public function query( $query_args = array(), $url = array(), $strict = true ) {

		global $mlp_registry;

		$this->strict = $strict;

		$this->query_args = wp_parse_args( $query_args );

		// parse URL
		$this->url = wp_parse_args( $url, $_GET );

		/**
		 * Fires after default params replaced from $_GET.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $url           Arguments for tracks where key track_id, value url.
		 */
		$this->url = apply_filters( 'mlp_url', $this->url );


		$post_type = isset( $this->query_args[ 'post_type' ] ) ? $this->query_args[ 'post_type' ] : 'any';

		$tax_query = isset( $this->query_args[ 'tax_query' ] ) ? $this->query_args[ 'tax_query' ] : array();

		$meta_query = isset( $this->query_args[ 'meta_query' ] ) ? $this->query_args[ 'meta_query' ] : array();


		$registered_tax_tracks = $mlp_registry->get_taxonomy_tracks( $post_type );

		$white_tax_tracks = $this->get_white_list_tracks( $registered_tax_tracks, $this->url );

		$this->query_args[ 'tax_query' ] = $this->prepare_tax_query( $tax_query, $white_tax_tracks );


		$registered_meta_tracks = $mlp_registry->get_meta_tracks( $post_type );

		$white_meta_tracks = $this->get_white_list_tracks( $registered_meta_tracks, $this->url );

		$this->query_args[ 'meta_query' ] = $this->prepare_meta_query( $meta_query, $white_meta_tracks );

		/**
		 * Fires before the request is sent.
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $query_args    Arguments for WP_Query.
		 */
		$this->query_args = apply_filters( 'mlp_query', $this->query_args );

		// execute query & save result
		$this->last_query = new WP_Query( $this->query_args );


		if ( false === $this->strict ) {

			/**
			 * Fires if strict mode deactivated.
			 *
			 * @since      1.0.0
			 *
			 * @param      object         $this          Instance of class.
			 */
			do_action_ref_array( 'mlp_query_post_processing', array( &$this ) );

		}


		return $this->last_query;
	}


}
