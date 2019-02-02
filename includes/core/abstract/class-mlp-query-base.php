<?php

/**
 * The file that defines the abstract class for query results.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/core/abstract
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
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @var        object         $last_query    .
	 */
	public $last_query;

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
	 * @since      1.0.0
	 *
	 * @access     protected
	 */
	protected function prepare_tax_query( $tax_query = array(), $args = array() ) {

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
	protected function prepare_meta_query( $meta_query = array(), $args = array() ) {

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

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     bool           .
	 */
	public function is_strict() {
		return (bool) $this->strict;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     array          .
	 */
	public function get_query_args() {
		return $this->query_args;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @return     array          .
	 */
	public function get_url() {
		return $this->url;
	}


}
