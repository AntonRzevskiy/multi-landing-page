<?php

/**
 * The file that defines the class track meta regexp column for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track type regexp-column.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Meta_Regexp_Column_Admin extends MLP_Track_Meta {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $track ) {

		$track = wp_parse_args( $track, array(

			'full_match' => false,

		) );

		// add postfix
		$track[ 'track_id' ] .= '-admin';

		parent::__construct( $track );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function fill_meta_query( $url, $track, $strict ) {

		return false;
	}


}
