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
	 * Include metadata functions.
	 *
	 * @since      1.0.0
	 *
	 * @trait
	 */
	use MLP_Metadata;

	/**
	 * Include display functions.
	 *
	 * @since      1.0.0
	 *
	 * @trait
	 */
	use MLP_Display_Metabox;

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
	 * @var        function       $fill_meta_query .
	 */
	protected $fill_meta_query;

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $args          .
	 */
	public function __construct( $args ) {

		$args = wp_parse_args( $args, array(

			'object_type'     => 'post',
			'track_id'        => '',
			'full_match'      => true,
			'post_type'       => array(),
			'meta_box'        => array(),
			'fill_meta_query' => array( $this, 'fill_meta_query' ),

		) );

		$this->args = $args;

		$this->type = 'meta';

		$this->object_type = $args[ 'object_type' ];

		$this->track_id = $args[ 'track_id' ];

		$this->full_match = $args[ 'full_match' ];

		$this->post_type = is_array( $args[ 'post_type' ] ) ? $args[ 'post_type' ] : array( $args[ 'post_type' ] );

		if ( false !== $args[ 'meta_box' ] ) {

			$this->meta_box = wp_parse_args( $args[ 'meta_box' ], array(

				'id'            => $this->track_id,
				'title'         => $this->track_id,
				'callback'      => array( $this, 'display_metabox_init' ),
				'screen'        => $this->post_type,
				'context'       => 'side',
				'priority'      => 'low'

			) );

		}

		$this->fill_meta_query = $args[ 'fill_meta_query' ];

		// Init
		$this->init();

	}

	/**
	 * .
	 *
	 * @since      1.0.0
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
		do_action_ref_array( 'mlp_init_metabox_track_display', array( &$this ) );

		/**
		 * .
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}_display", array( &$this ) );

		$this->display_track_html( $this );

	}

	/**
	 * .
	 *
	 * @since      1.0.0
	 *
	 * @param      int            $post_id       .
	 */
	public function save_post_data_init( $post_id ) {

		if ( false === $this->verify_save( $post_id ) ) {

			return;
		}

		$data = $this->get_saving_data();

		/**
		 * .
		 *
		 * This hook will fires if the user’s rights pass.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( 'mlp_init_metabox_track_save', array( &$this, $post_id, $data ) );

		/**
		 * .
		 *
		 * This hook will fires if the user’s rights pass.
		 *
		 * @since      1.0.0
		 *
		 * @param      object         $this          .
		 */
		do_action_ref_array( "mlp_init_metabox_track_{$this->track_id}_save", array( &$this, $post_id, $data ) );

		$this->define_process_meta( $this, $post_id, $data );

	}


}
