<?php

/**
 * The file that defines the trait for work with metadata.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track meta functions.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
trait MLP_Metadata {


	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      int            $post_id       .
	 */
	public function verify_save( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) {

			return false;
		}

		if ( false === isset( $_POST[ $this->track_id ] ) ) {

			return false;
		}

		if ( false === isset( $_POST[ "_wp_nonce_{$this->track_id}" ] ) ) {

			return false;
		}

		if ( false === wp_verify_nonce( $_POST[ "_wp_nonce_{$this->track_id}" ], "_wp_nonce_{$this->track_id}" ) ) {

			return false;
		}

		if( false === current_user_can( 'edit_post', $post_id ) ) {

			return false;
		}

		return true;
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_saving_data() {

		if ( isset( $_POST[ $this->track_id ] ) ) {

			return $_POST[ $this->track_id ];
		}

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function define_process_meta( $track, $post_id = NULL, $new_data = array() ) {

		global $post;

		$post_id = intval( ( $post_id ? $post_id : $post->ID ) );

		$args = $track->get( 'args' );

		// get array metadata
		$old_data = $this->get_metadata( $track, $post_id );

		$new_data = $this->prepare_data( $track, $post_id, $new_data, $old_data );

		if ( $this->is_valid_data( $track, $post_id, $new_data, $old_data ) ) {

			if ( count( $old_data ) > count( $new_data ) ) {

				$this->delete_metadata( $track, $post_id, $new_data, $old_data );

			}

			if ( $new_data ) {

				$this->save_metadata( $track, $post_id, $new_data, $old_data );

			}

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function prepare_data( $track, $post_id, $new_data, $old_data ) {

		if ( $new_data && false === is_array( $new_data ) ) {

			$new_data = array( $new_data );

		} else {

			$new_data = array();

		}

		return wp_slash( $new_data );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function is_valid_data( $track, $post_id, $new_data, $old_data ) {

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 * @param      array          $new_data      .
		 * @param      array          $old_data      .
		 */
		return apply_filters( 'mlp_is_valid_data', true, $track, $post_id, $new_data, $old_data  );
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_metadata( $track, $post_id = NULL ) {

		global $post;

		$post_id = intval( ( $post_id ? $post_id : $post->ID ) );

		$data = get_metadata( $track->get( 'object_type' ), $post_id, $track->get( 'track_id' ), false );

		if ( $data ) {

			return wp_unslash( $data );
		}

		return array();
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function save_metadata( $track, $post_id, $new_data, $old_data ) {

		$object_type = $track->get( 'object_type' );

		$track_id = $track->get( 'track_id' );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $new_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$new_data = apply_filters( 'mlp_save_metadata', $new_data, $track, $post_id );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      array          $new_data      .
		 * @param      object         $track         .
		 * @param      int            $post_id       .
		 */
		$new_data = apply_filters( "mlp_save_{$object_type}_metadata", $new_data, $track, $post_id );

		foreach ( $new_data as $key => $new_value ) {

			$old_value = ( isset( $old_data[ $key ] ) ? $old_data[ $key ] : '' );

			update_metadata( $object_type, $post_id, $track_id, $new_value, $old_value );

		}

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function delete_metadata( $track, $post_id, $new_data, $old_data ) {

		$object_type = $track->get( 'object_type' );

		$track_id = $track->get( 'track_id' );

		$old_data = array_slice( $old_data, count( $new_data ) );

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
		$old_data = apply_filters( "mlp_delete_{$object_type}_metadata", $old_data, $track, $post_id );

		foreach ( $old_data as $old_value ) {

			delete_metadata( $object_type, $post_id, $track_id, $old_value );

		}

	}


}
