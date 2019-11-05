<?php

/**
 * The file that defines the class track meta regexp column mask for mlp.
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
class MLP_Track_Meta_Regexp_Column_Mask extends MLP_Track_Meta_Regexp_Column {


	/**
	 * The number of words in regular expression.
	 *
	 * @since      1.0.0
	 *
	 * @var        int            $words_count   .
	 */
	public $words_count = 0;

	/**
	 * The number of words in mask.
	 *
	 * @since      1.0.0
	 *
	 * @var        int            $input_count   .
	 */
	public $input_count = 0;

	/**
	 * Is the mask wrapped in quotes?
	 *
	 * @since      1.0.0
	 *
	 * @var        bool           $strict_regexp .
	 */
	public $strict_regexp = false;

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 */
	public function __construct( $track ) {

		// hide meta box
		$track[ 'meta_box' ] = false;

		parent::__construct( $track );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function get_saving_data() {

		if ( isset( $_POST[ $this->track_id . '-admin' ] ) ) {

			return $_POST[ $this->track_id . '-admin' ];
		}

		return '';
	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function prepare_data( MLP_Metadata $track, $post_id, $new_data, $old_data ) {

		$new_data = parent::prepare_data( $track, $post_id, $new_data, $old_data );

		array_walk( $new_data, function( &$new_value ) {

			$mask = new MLP_Mask( wp_unslash( $new_value ) );

			$new_value = wp_slash( $mask->parse()->create_regexp() );

			$this->words_count = $mask->result[ 'words_count' ];

			$this->input_count = $mask->result[ 'input_count' ];

			$this->strict_regexp = $mask->result[ 'strict' ];

		});

		return $new_data;
	}


}
