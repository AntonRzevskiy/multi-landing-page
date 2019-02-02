<?php

/**
 * The file that defines the class track meta regexp column for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes/ext
 */

/**
 * Define the track type regexp-column.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Meta_Regexp_Column_Input_Count extends MLP_Track_Meta {


	/**
	 * Link to the parent class.
	 *
	 * @since      1.0.0
	 *
	 * @var        MLP_Track_Meta_Regexp_Column_Mask  $track_mask  .
	 */
	protected $track_mask;

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $track, $track_mask ) {

		add_filter( 'mlp_url', array( $this, 'treat_url' ) );

		$this->track_mask = $track_mask;

		// save origin track id
		$track[ 'origin_track_id' ] = $track[ 'track_id' ];

		// add postfix
		$track[ 'track_id' ] .= '-input_count';

		// hide meta box
		$track[ 'meta_box' ] = false;

		parent::__construct( $track );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function fill_meta_query( $url, $track, $strict ) {

		if ( '' === $url || false === $this->is_strict() ) {

			return false;
		}

		return array(

			'relation' => 'OR',

			array(

				'key'      => $this->track_id,
				'value'    => $url,
				'compare'  => '=',
				'type'     => 'NUMERIC',

			),

			array(

				'key'      => $this->track_id,
				'compare'  => 'NOT EXISTS',

			),

		);

	}

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

		if ( $this->track_mask->strict_regexp ) {

			return $this->track_mask->input_count;
		}

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function treat_url( $url ) {

		$origin = $this->args[ 'origin_track_id' ];

		if ( true === isset( $url[ $origin ] ) && false === isset( $url[ $this->track_id ] ) ) {

			// get all words count
			$url[ $this->track_id ] = count( explode( ' ', $url[ $origin ] ) );

		}

		return $url;
	}


}
