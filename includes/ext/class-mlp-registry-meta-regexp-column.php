<?php

/**
 * The file that defines the class that register track regexp-column for mlp.
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
class MLP_Registry_Meta_Regexp_Column {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		add_filter( 'mlp_add_track', array( $this, 'define_track' ), 10, 2 );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function define_track( $tracks, $track ) {

		if ( 'regexp-column' === $track[ 'type' ] ) {

			$tracks[] = new MLP_Track_Meta_Regexp_Column_Admin( $track );

			$tracks[] = new MLP_Track_Meta_Regexp_Column( $track );

		}

		return $tracks;
	}


}
