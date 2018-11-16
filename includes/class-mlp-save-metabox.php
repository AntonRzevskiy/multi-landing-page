<?php

/**
 * The file that defines the class for save or delete content of metabox.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track meta save by types.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Save_Metabox {


	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		add_action( 'mlp_init_metabox_track_save', array( $this, 'define_metabox_save' ), 10, 3 );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function define_metabox_save( $track, $post_id, $new_data ) {

		if ( false === isset( $track->args[ 'metabox_html' ][ 'type' ] ) ) {

			return;
		}

		// get array metadata
		$old_data = get_metadata( $track->object_type, $post_id, $track->track_id, false );

		$new_data = $this->prepare( $track, $post_id, $new_data, $old_data );

		switch ( $track->args[ 'metabox_html' ][ 'type' ] ) {

			case 'text':

				if ( $this->valid( $track, $post_id, $new_data, $old_data ) ) {

					if ( $new_data ) {

						$this->save_metadata( $track, $post_id, $new_data, $old_data );

					} else {

						$this->delete_metadata( $track, $post_id, $old_data );

					}

				}

			break;

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function prepare( $track, $post_id, $new_data, $old_data ) {

		return wp_slash( $new_data );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function valid( $track, $post_id, $new_data, $old_data ) {

		return true;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function save_metadata( $track, $post_id, $new_data, $old_data ) {

		if ( false === is_array( $old_data ) ) {

			$old_data = array( $old_data );
		}

		if ( empty( $old_data ) ) {

			$old_data = array( '' );
		}

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$old_data = apply_filters( 'mlp_save_metadata', $old_data, $track, $post_id );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$old_data = apply_filters( "mlp_save_{$track->object_type}_metadata", $old_data, $track, $post_id );

		foreach ( $old_data as $old_value ) {

			update_metadata( $track->object_type, $post_id, $track->track_id, $old_value );

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function delete_metadata( $track, $post_id, $old_data ) {

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$old_data = apply_filters( 'mlp_delete_metadata', $old_data, $track, $post_id );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $old_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$old_data = apply_filters( "mlp_delete_{$track->object_type}_metadata", $old_data, $track, $post_id );

		foreach ( $old_data as $old_value ) {

			delete_metadata( $track->object_type, $post_id, $track->track_id, $old_value );

		}

	}


}
