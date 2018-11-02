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
class MLP_Track_Meta extends MLP_Track_Base {


	/**
	 * Params for add_meta_box function.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $meta_box      .
	 */
	protected $meta_box = array();

	/**
	 * Params for WP_Query.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $meta_query    .
	 */
	protected $meta_query = array();

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $args          .
	 */
	public function __construct( $args ) {

		$this->args = wp_parse_args( $args );

		$this->type = 'meta';

		$this->track_id = $args[ 'track_id' ];

		$this->full_match = isset( $args[ 'full_match' ] ) ? $args[ 'full_match' ] : true;

		$this->post_type = is_array( $args[ 'post_type' ] ) ? $args[ 'post_type' ] : array( $args[ 'post_type' ] );

		$this->meta_box = wp_parse_args( $args[ 'meta_box' ], array(

			'id'            => $this->track_id,
			'title'         => $this->track_id,
			'callback'      => array( $this, 'display_metabox_init' ),
			'screen'        => $this->post_type,
			'context'       => 'side',
			'priority'      => 'low'

		) );

		$this->meta_query = wp_parse_args( $args[ 'meta_query' ], array(

			// It can be any array according to the documentation WP.
			// 'key'      => $this->track_id,
			// 'value'    => '', // will be defined later
			// 'compare'  => $this->is_strict() ? '=' : 'LIKE',
			// 'type '    => 'CHAR',

		) );

		// Init
		$this->init();

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function init() {

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( "mlp_init_track_{$this->track_id}", array( &$this ) );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 */
	public function display_metabox_init() {

		wp_nonce_field( "_wp_nonce_{$this->track_id}", "_wp_nonce_{$this->track_id}" );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}", array( &$this ) );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      int            $post_id       .
	 */
	public function save_post_data_init( $post_id ) {

		if ( false === isset( $_POST[ "_wp_nonce_{$this->track_id}" ] ) ) {

			return;
		}

		if ( false === wp_verify_nonce( $_POST[ "_wp_nonce_{$this->track_id}" ], "_wp_nonce_{$this->track_id}" ) ) {

			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;
		}

		if( false === current_user_can( 'edit_post', $post_id ) ) {

			return;
		}

		$data = $_POST[ "_wp_nonce_{$this->track_id}" ] );

		/**
		 * .
		 *
		 * This hook will fires if the user’s rights pass.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}_save", array( &$this, $post_id, $data );

	}


}
