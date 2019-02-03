<?php

/**
 * The file that defines the class track meta for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track meta.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Meta extends MLP_Track_Meta_Base {


	/**
	 * Fill part of meta query.
	 *
	 * @since      1.0.0
	 *
	 * @param      string         $url           URL value.
	 * @param      object         $track         Current instance.
	 * @param      bool           $strict        Is the query strict.
	 *
	 * @return     array          Part of meta query.
	 */
	public function fill_meta_query( $url, $track, $strict ) {

		if ( '' === $url && false === $this->is_strict() ) {

			return false;
		}

		if ( $url && false === $this->is_strict() ) {

			return array(

				'relation' => 'OR',

				array(

					'key'      => $this->track_id,
					'value'    => $url,
					'compare'  => 'LIKE',
					'type'     => 'CHAR',

				),

				array(

					'key'      => $this->track_id,
					'compare'  => 'NOT EXISTS',

				),

			);
		}

		return array(

			'key'      => $this->track_id,
			'value'    => $url,
			'compare'  => '=',
			'type'     => 'CHAR',

		);

	}

	/**
	 * Vertify save track.
 	 *
	 * @since      1.0.0
	 *
	 * @param      int/string     $post_id       ID of page.
	 *
	 * @return     bool           True if current user have permissions. False if fail.
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
	 * Get data for save.
	 *
	 * @since      1.0.0
	 *
	 * @return     mixed          Saving data.
	 */
	public function get_saving_data() {

		if ( isset( $_POST[ $this->track_id ] ) ) {

			return $_POST[ $this->track_id ];
		}

		return '';
	}

	/**
	 * Prepare new data.
	 *
	 * @since      1.0.0
	 *
	 * @param      object         $track         Instance of current track.
	 * @param      int/string     $post_id       ID of page.
	 * @param      array          $new_data      Saving data.
	 * @param      array          $old_data      Old data from DB.
	 *
	 * @return     array          New data, equal in type to old.
	 */
	public function prepare_data( $track, $post_id, $new_data, $old_data ) {

		if ( $new_data && false === is_array( $new_data ) ) {

			$new_data = array( $new_data );

		}

		if ( is_null( $new_data ) || '' === $new_data ) {

			$new_data = array();

		}

		return $new_data;
	}


}
