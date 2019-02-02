<?php

/**
 * The file that defines the class that register track regexp-column-mask for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/ext
 */

/**
 * Define the track type regexp-column-mask.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Registry_Meta_Regexp_Column_Mask {


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

		if ( 'regexp-column-mask' === $track[ 'type' ] ) {

			$tracks[] = new MLP_Track_Meta_Regexp_Column_Admin( $track );

			$column_mask = new MLP_Track_Meta_Regexp_Column_Mask( $track );

			$tracks[] = $column_mask;

			$tracks[] = new MLP_Track_Meta_Regexp_Column_Words_Count( $track, $column_mask );

			$tracks[] = new MLP_Track_Meta_Regexp_Column_Input_Count( $track, $column_mask );

		}

		return $tracks;
	}


}
