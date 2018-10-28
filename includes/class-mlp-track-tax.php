<?php

/**
 * The file that defines the class track taxonomy for mlp.
 *
 *
 * @since      1.0.0
 *
 * @package    multi-landing-page
 * @subpackage multi-landing-page/includes
 */

/**
 * Define the track taxonomy.
 *
 *
 * @since      1.0.0
 *
 * @author     Anton Rzhevskiy <antonrzhevskiy@gmail.com>
 */
class MLP_Track_Tax extends MLP_Track_Base {


	/**
	 * Params for register_taxonomy function.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $taxonomy_args .
	 */
	protected $taxonomy_args = array();

	/**
	 * Params for WP_Query.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $tax_query     .
	 */
	protected $tax_query = array();

	/**
	 * Add filter select in admin panel.
	 *
	 * @since      1.0.0
	 *
	 * @var        bool           $filter        .
	 */
	protected $filter = false;

	/**
	 * Params for wp_dropdown_categories function.
	 *
	 * @since      1.0.0
	 *
	 * @var        array          $filter_args   .
	 */
	protected $filter_args = array();

	/**
	 * Constructor.
	 *
	 * @since      1.0.0
	 *
	 * @param      array          $args          .
	 */
	public function __construct( $args ) {

		$this->args = wp_parse_args( $args );

		$this->type = 'taxonomy';

		$this->track_id = $args[ 'track_id' ];

		$this->full_match = true;

		$this->post_type = is_array( $args[ 'post_type' ] ) ? $args[ 'post_type' ] : array( $args[ 'post_type' ] );

		$this->taxonomy_args = wp_parse_args( $args[ 'taxonomy_args' ], array(

			'hierarchical'       => false,
			'rewrite'            => false,
			'publicly_queryable' => false,
			'query_var'          => false,

		) );

		$this->tax_query = wp_parse_args( $args[ 'tax_query' ], array(

			'taxonomy' => $this->track_id,
			'field'    => 'name',
			'terms'    => array(), // will be filled later
			'operator' => 'IN',

		) );

		$this->filter = $args[ 'filter' ];

		if ( $this->filter ) {

			$this->filter_args = wp_parse_args( $args[ 'filter_args' ], array(

				'show_option_none'  => __( 'All' ),
				'option_none_value' => '',
				'show_count'        => true,
				'hide_empty'        => true,
				'echo'              => true,
				'name'              => "taxonomy={$this->track_id}&term",
				'taxonomy'          => $this->track_id,
				'hide_if_empty'     => true,
				'value_field'       => 'slug',
				'selected'          => null, // will be defined later

			) );

		}

		// Init
		$this->init();

	}

	/**
	 * .
	 *
	 * @since      1.0.0          .
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


}
